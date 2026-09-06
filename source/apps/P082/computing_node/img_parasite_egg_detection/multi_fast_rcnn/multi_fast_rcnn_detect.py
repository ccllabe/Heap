from __future__ import absolute_import
from __future__ import division
from __future__ import print_function

import os
import stat
import cv2
import matplotlib.pyplot as plt
import numpy as np
import tensorflow as tf
import gc
#os.environ["CUDA_VISIBLE_DEVICES"] = "-1"
from lib.config import config as cfg
from lib.utils.nms_wrapper import nms
from lib.utils.test import im_detect
#from nets.resnet_v1 import resnetv1
from lib.nets.vgg16 import vgg16
from lib.utils.timer import Timer
import time

def multi_fast_rcnn_run(weight_path, demonet, egg_class, img_path, place_record_path, ori_w, ori_h):
    # default definition
    # class definition
    CLASSES = ('__background__',
    'Trichuris trichiura egg',
    'Toxocara canis egg',
    'Clonorchis sinensis egg',
    'Taenia/Echinococcus egg',
    'Fasciola hepatica egg',
    'Schistosoma japonicum egg',
    'Diphyllobothrium latum egg',
    'Enterobius vermicularis egg')
    n_classes = len(CLASSES)
    # use vgg16 base
    NETS= {'vgg16':'vgg16.ckpt'}
    #weight file check
    tfmodel = os.path.join(weight_path, NETS[demonet])

    if not os.path.isfile(tfmodel + '.meta'):
        print(tfmodel)
        return
    # set config
    tfconfig = tf.ConfigProto(allow_soft_placement=True)
    #tfconfig.gpu_options.allow_growth = True
    tfconfig.gpu_options.allow_growth = False
    # init session
    sess = tf.Session(config=tfconfig)
    # load network
    if demonet == 'vgg16':
        net = vgg16(batch_size=1)
    #elif demonet == 'res101':
        #net = resnetv1(batch_size=1, num_layers=101)
    else:
        raise NotImplementedError
    # create the structure of the net having a certain shape (which depends on the number of classes)
    net.create_architecture(sess, "TEST", n_classes, tag='default', anchor_scales=[8, 16, 32])
    saver = tf.train.Saver()
    saver.restore(sess, tfmodel)
    #print('Loaded network {:s}'.format(tfmodel))
    filelist = os.listdir(img_path)
    record_bigplace = open(place_record_path,"w")
    for file in filelist:
        oldDir = os.path.join(img_path, file)
        #print(oldDir)
        file_ar = file.split('.')
        img = cv2.imread(oldDir)
        scores, boxes = im_detect(sess, net, img)
        # Visualize detections for each class
        CONF_THRESH = 0.5
        NMS_THRESH = 0.1
        for cls_ind, cls in enumerate(CLASSES[1:]):
            cls_ind += 1  # because we skipped background
            if(cls==egg_class):
                cls_boxes = boxes[:, 4 * cls_ind:4 * (cls_ind + 1)]
                cls_scores = scores[:, cls_ind]
                dets = np.hstack((cls_boxes,cls_scores[:, np.newaxis])).astype(np.float32)
                keep = nms(dets, NMS_THRESH)
                dets = dets[keep, :]
                #vis_detections(img, cls, dets, thresh=CONF_THRESH)
                inds = np.where(dets[:, -1] >= CONF_THRESH)[0]
                if len(inds) != 0:
                    for i in inds:
                        bbox = dets[i, :4]
                        score = dets[i, -1]
                        display_txt = '{:s}, {:0.0f}, {:0.0f}, {:0.0f}, {:0.0f}, {:0.2f}, {:s}'.format(file, bbox[0], bbox[1], bbox[2], bbox[3], score, cls)
                        #print(display_txt)
                        bbox = bbox.astype(int)
                        if(bbox[2]==bbox[0]):
                            if(bbox[0]>0):
                                bbox[0]-=1
                            else:
                                bbox[2]+=1
                        if(bbox[3]==bbox[1]):
                            if(bbox[1]>0):
                                bbox[1]-=1
                            else:
                                bbox[3]+=1
                        if (ori_w>(int(file_ar[2])+bbox[0])) and (ori_h>(int(file_ar[3])+bbox[1])):
                            record_string = str(int(file_ar[2])+bbox[0])+','+str(int(file_ar[3])+bbox[1])+','+str(int(file_ar[2])+bbox[2])+','+str(int(file_ar[3])+bbox[3])+','+str(int(score*100.0))
                            record_bigplace.writelines(record_string)
                            record_bigplace.writelines('\n')
    record_bigplace.close()
    os.chmod(place_record_path,stat.S_IREAD+stat.S_IWRITE+stat.S_IRGRP+stat.S_IWGRP+stat.S_IROTH+stat.S_IWOTH)
    gc.collect()




if __name__ == '__main__':
    egg_class = r'Trichuris trichiura egg'
    demonet='vgg16'
    weight_path=r'./checkpoints/version2'
    img_path = r'/home/ACCOUNT/P082/test/mvcut_imgs_300'
    place_record_path = r'/home/ACCOUNT/P082/test/place.txt'
    ori_w = 14100
    ori_h = 6900
    start_time = time.time()
    multi_fast_rcnn_run(weight_path, demonet, egg_class, img_path, place_record_path, ori_w, ori_h)
    end_time = time.time()
    spend_time = end_time-start_time
    print(spend_time)
