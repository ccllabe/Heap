#!/bin/bash
function read_dir(){
    a=`pwd`

    for file in `ls $1`
    do
        if [ -d $1"/"$file ]
        then
            read_dir $1"/"$file

        else
            filename=$(echo $file | cut -d"." -f 1)
            string=$(echo $1"/"$file | cut -d"/" -f 2)
            picture=$a"/"$1"/"$file
            dir_folder=$a"/"$dir"/"$string"/"$filename
#            echo $picture
#            echo $dir_folder
            python ~/html/project/gdal2tiles/gdal2tiles-multiprocess.py -l -p raster --processes 10 -w none $picture $dir_folder
            python ~/html/project/gdal2tiles/gdal2tiles-multiprocess.py -e -l -p raster  --processes 10 -w none $picture $dir_folder
        fi
    done
}

dir=$2
read_dir $1