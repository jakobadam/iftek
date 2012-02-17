<?php
// Kilde til parser: http://simplehtmldom.sourceforge.net/
include('simple_html_dom.php');

// Dato
$actual_date = mktime(0,0,0,date("m"),date("d")+4,date("Y"));

// Parse lectio og print lektier og aflyste timer
parse_lectio($actual_date, 1903012531);


function parse_lectio($date, $elev_id) {
	
	// Udregn ugenummer. Fx uge 7
	$weekyear = get_weeknumber($date);
	
	// I tabellen over skemaet, er der 6 colonner. Første er tidspunkt på dagen, næste er mandag osv.
	// Colonne 0: Tidspunkt
	// Colonne 1: Mandag
	// Colonne 2: Tirsdag
	// Colonne 3: Onsdag
	// Colonne 4: Torsdag
	// Colonne 5: Fredag
	
	// Find ud af hvilken dag i ugen vi arbejder med. 0 er søndag, 1 er mandag og 6 er lørdag
	$dayofweek = date("w", $date);
	
  	// Hent lectio hjemmesiden
  	$html = file_get_html('https://www.lectio.dk/lectio/256/SkemaNy.aspx?type=elev&elevid=' . $elev_id . '&week=' . $weekyear);
	
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
			if ($td_index == $dayofweek) {
				
				// Find alle links (<a> tags) i cellen. De linker til de forskellige timer
				foreach($td->find('a') as $link) {
					
					// Tjek om <a> tagget har en href (link)
					if ($link->href != null) {
						$href = $link->href;
						parse_lectio_aktivitet($href);
					}
				}
			}
			
			// Tæl index op
			$td_index++;
		}	
	}
}

/*
 * Parse en konkret aktivitet / time
 */
function parse_lectio_aktivitet($url) {
	
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
			if ($th_text == "Tidspunkt:")
				$tidspunkt = $td_text;
			else if ($th_text == "Hold:")
				$hold = $td->find('a', 0)->innertext; // Hold er altid et link, fjern det
			else if ($th_text == "Note:")
				$note = trim($td_text);
			else if ($th_text == "Lektier:")
				$lektier = trim($td_text);
			else if ($th_text == "Status:")
				$status = $td_text;
		}
	}
	
	$lektier_samlet = '';
	
	// Tjek at lektier indholder tekst
	if ($lektier != null && strlen($lektier) > 0)
		$lektier_samlet = $lektier;
	
	// Tjek at note indholder tekst
	if ($note != null && strlen($note) > 0) {
		
		// Hvis lektier indholdte tekst, tilføj bindestreg mellem lektierne.
		if (strlen($lektier_samlet) > 0)
			$lektier_samlet = $lektier_samlet . ' - ';
		
		// Tilføj note til samlet lektier
		$lektier_samlet = $lektier_samlet . $note;
	}
	
	// Print kun indholdet hvis der er lektier eller timen er aflyst
	if (strlen($lektier_samlet) > 0 || $status == 'Aflyst') {
		
		echo '<br/><br/>';
		
		// Tjek om timen er aflyst
		if ($status == 'Aflyst')
			echo '<b><font color=\'red\'>Aflyst</font></b> ';
		
		echo '<b>Hold:</b> ' . $hold . ' <b>Tidspunkt:</b> ' . $tidspunkt . '<br/>';
		echo '<b>Lektier: </b> ' . $lektier_samlet;
	}
}

/*
 * Få ugenummer samt årstal ud fra den
 * givne dato.
 * Benyttes som parameter til lectio for
 * at åbne et skema i en bestemt uge.
 */
function get_weeknumber($date) {
	$weekyear = "";
	$week = (int)date('W', $date);
	$year = (int)date('Y', $date);
	if ($week < 10)
		$weekyear = "0" . $week;
	else
		$weekyear = $week;
	
	$weekyear = $weekyear . $year; 
	
	return $weekyear; 
}

?>