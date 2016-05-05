#!/bin/bash
mogrify -format jpg -resize x250 -quality 100 study_images/*_d.png
mogrify -format jpg -resize x250 -quality 100 study_images/*_s.png
mogrify -format jpg -resize x250 -quality 100 study_images/*_drawing.png
