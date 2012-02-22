<?php
// Kilde til parser: http://simplehtmldom.sourceforge.net/
include('simple_html_dom.php');


function lectioGetSchemaURL($student_id, $date){
    // Udregn ugenummer og år. Fx uge 7 år 2012
    // er 072012
    $week_year = lectioGetWeekYear($date);

    return 'https://www.lectio.dk/lectio/256/SkemaNy.aspx?type=elev&elevid=' . $student_id . '&week=' . $week_year;
}

function lectioGetSchema($student_id, $date){    
    // Hent lectio hjemmesiden
    $url = lectioGetSchemaURL($student_id, $date);
    return file_get_html($url);
}


/**
 * Henter elevens aktiviteter fra lectio for en given dag og returnerer dette som HTML.
 */
function lectioGetActivities($student_id, $date) {
	$html_out = "";
    
    // Find ud af hvilken dag i ugen vi arbejder med. 0 er søndag, 1 er mandag og 6 er lørdag
    $day_of_week = date("w", $date);
	// I tabellen over skemaet, er der 6 colonner. Første er tidspunkt på dagen, næste er mandag osv.
	// Colonne 0: Tidspunkt
	// Colonne 1: Mandag
	// Colonne 2: Tirsdag
	// Colonne 3: Onsdag
	// Colonne 4: Torsdag
	// Colonne 5: Fredag
	
  	$html = lectioGetSchema($student_id, $date); 
	
	// Find skemaet - en tabel med css class = s2skema
	$skema = $html->find('.s2skema', 0); // 0 betyder at vi kun ønsker, at finde første element med class = s2skema
	
	// Gennemløb alle rækker i tabellen (<tr> tags)
	foreach($skema->find('tr') as $tr) {
		
		// Index så vi kan styre, hvilken celle vi arbejder med.
		// Er det celle 1 = mandag, celle 5 = fredag osv.
		$td_index = 0;
		
		// For hver række gennemløb dens celler (<td> tags)
		foreach($tr->find('td') as $td) {
					
			// Medtag kun colonner, der gælder for den korrekte dag. Fx er $td_index 1 = mandag
			if ($td_index == $day_of_week) {
				
				// Find alle links (<a> tags) i cellen. De linker til de forskellige timer
				foreach($td->find('a') as $link) {
					
					// Tjek om <a> tagget har en href (link)
					if ($link->href != null) {
						$href = $link->href;
						$html_out = $html_out + lectioParseActivity($href);
					}
				}
			}
			
			// Tæl index op
			$td_index++;
		}	
	}
    return $html_out;
}

/*
 * Parse en konkret aktivitet / time
 */
function lectioParseActivity($url) {
    
	// Hent siden
	$html = file_get_html('https://www.lectio.dk' . $url);
	
	// Find tabel med al information om aktiviteten
	$div = $html->find('.islandContent', 0);
	$table = $div->find('table', 0); 
	
	// Variabler der skal indholde følgende oplysninger
	$tidspunkt = '';
	$hold = '';
	$note = '';
	$lektier = '';
	$status = ''; 
	
	// Gennemløb alle rækker i tabellen (<tr> tags)
	foreach($table->find('tr') as $tr) {
		
		// Hver række indholder et <th> og <td> tag. 
		// <th> indholder overskriften, <td> indholder selve indholdet
		$th = $tr->find('th', 0);
		$td = $tr->find('td', 0);
		
		// Tjek at begge tags findes før vi læser indholdet
		if ($th != null && $td != null) {
			
			// Fjerner <th> og <td> fra teksten, så vi kun har den rene tekst uden tags
			// substr(tekst, start, længde)
			// Vi starter 4 tegn inde. Længden er hele længden - 9 tegn. De 9 tegn er længden af: <th></th>
			$th_text = substr($th, 4, strlen($th) - 9);
			$td_text = substr($td, 4, strlen($td) - 9);
			
			// Udfyld variablerne
			if ($th_text == "Tidspunkt:"){
				$tidspunkt = $td_text;			    
			}
			else if ($th_text == "Hold:"){
				$hold = $td->find('a', 0)->innertext; // Hold er altid et link, fjern det			    
			}
			else if ($th_text == "Note:"){
				$note = trim($td_text);		    
			}
			else if ($th_text == "Lektier:"){
				$lektier = trim($td_text);			    
			}
			else if ($th_text == "Status:"){
				$status = $td_text;			    
			}
		}
	}
	
    // lektier samlet er note feltet + lektier feltet
	$lektier_samlet = '';
	
	// Tjek at lektier indholder tekst
	if ($lektier != null && strlen($lektier) > 0){
		$lektier_samlet = $lektier;	    
	}
    
	// Tjek at note indholder tekst
	if ($note != null && strlen($note) > 0) {
		
		// Hvis lektier indholdte tekst, tilføj bindestreg mellem lektier og note.
		if (strlen($lektier_samlet) > 0)
			$lektier_samlet = $lektier_samlet . ' - ';
		
		// Tilføj note til samlet lektier
		$lektier_samlet = $lektier_samlet . $note;
	}
    
	// Print kun indholdet hvis der er lektier eller timen er aflyst
	$html = "";

	if (strlen($lektier_samlet) > 0 || $status == 'Aflyst') {
		    
		$html = $html . '<br/><br/>';
		
		// Tjek om timen er aflyst
		if ($status == 'Aflyst'){
			$html = $html . '<b><font color="red">Aflyst</font></b>';		    
		}
		
		$html = $html . '<b>Hold:</b> ' . $hold . ' <b>Tidspunkt:</b> ' . $tidspunkt . '<br/>';
		$html = $html . '<b>Lektier: </b> ' . $lektier_samlet;
	}
    
    return $html;
}

/*
 * Få ugenummer samt årstal ud fra den
 * givne dato.
 * Benyttes som parameter til lectio for
 * at åbne et skema i en bestemt uge.
 */
function lectioGetWeekYear($date) {
    
	$week_year = "";
    
	$week = (int)date('W', $date);
	$year = (int)date('Y', $date);
    
	if ($week < 10)
		$week_year = "0" . $week;
	else
		$week_year = $week;
	
	$week_year = $week_year . $year; 
	
	return $week_year; 
}

?>