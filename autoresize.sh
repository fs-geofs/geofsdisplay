#!/bin/bash

# watches a given folder & resizes all images to a given size.
# to disable resizing, add .noresize to the filename
# also, pdfs are auto converted to png.

# depends on (ubuntu)packages 'inotify-tools' and 'imagemagick'

watchdir="$(pwd)/displaycontent/plakate"
targetresolution='746x'
imgpattern='\.(png|jpg|jpeg|gif)$'

echo "watching $watchdir"

inotifywait -m -e CREATE $watchdir --format '%f' |
  while read filename; do
    echo "'$filename' created!"
    filepath=${watchdir}/${filename}
    filenoext=${filename%.*}

    # file is an image
    if [[ $filename =~ .+\.(png|jpg|jpeg|gif)$ ]]; then

      # file is flagged with noresize in filename
      if [[ $filename =~ .+\.noresize.+ ]]; then
        echo "nothing to do for '$filename'"
        exit 0;
      fi

      # resize image, store with ".noresize" appended
      # to filename, and remove old image
      echo "resizing '$filename'"
      convert "$filepath" -resize $targetresolution "$watchdir/$filenoext.noresize.png"
      rm -f "$filepath"

    # file is a pdf
    elif [[ $filename =~ .+\.pdf$ ]]; then
      # convert pdf to png
      echo "converting '$filename' to png"
      convert -density 300 -trim "$filepath" "$watchdir/$filenoext.png"
      rm -f "$filepath"

    else
      echo "no match for '$filename'"
    fi
  done
