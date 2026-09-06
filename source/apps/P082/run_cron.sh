#!/bin/sh
while true
do
    /home/ACCOUNT/miniconda3/envs/tf-1.0.0-gpu/bin/python /home/ACCOUNT/P082/computing_node/img_size_segmentation/main.py
    /home/ACCOUNT/miniconda3/envs/tf-1.0.0-gpu/bin/python /home/ACCOUNT/P082/computing_node/img_parasite_egg_detection/main.py
    sleep 10
done
