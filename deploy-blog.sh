#!/bin/bash
source config.cfg
rsync -av blog $USER@193.200.45.133:public_html
