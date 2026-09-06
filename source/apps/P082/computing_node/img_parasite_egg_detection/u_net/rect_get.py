import numpy as np
import cv2
from skimage import io
import os

#ori: 256*256 detect file; ori_w,ori_h: ori img(haven't cutten) size
def img_contour_get(ori, resize_w, resize_h, filter_area, img_x, img_y, record_bigplace, score, ori_w, ori_h):
    test = ori.copy()
    h,w = test.shape[:2]
    mask = np.zeros((h+2,w+2),np.uint8)
    isbreak = False
    for i in range(h):
        for j in range(w):
            if(test[i][j]==0):
                seedPoint = (i,j)
                isbreak = True
                break
        if(isbreak):
            break

    cv2.floodFill(test, mask,seedPoint, 255)
    test_inv = cv2.bitwise_not(test)
    test_out = ori | test_inv
    _, contours, hierarchy = cv2.findContours(test_out, cv2.RETR_LIST, cv2.CHAIN_APPROX_SIMPLE)
    rw = int(resize_w/w)
    rh = int(resize_h/h)
    #test_out = cv2.resize(test_out, (resize_w, resize_h), interpolation=cv2.INTER_CUBIC)
    for c in contours:
        if(cv2.contourArea(c)>filter_area):
            (x, y, w, h) = cv2.boundingRect(c)
            #print(img_x+rw*x, img_y+rh*y, img_x+rw*x+rw*w, img_y+rh*y+rh*h)
            #place_array.append([img_x+rw*x, img_y+rh*y, img_x+rw*x+rw*w, img_y+rh*y+rh*h])
            if (ori_w>(img_x+rw*x)) and (ori_h>(img_y+rh*y)):
                record_string = str(img_x+rw*x)+','+str(img_y+rh*y)+','+str(img_x+rw*x+rw*w)+','+str(img_y+rh*y+rh*h)+','+str(int(score*100.0))
                record_bigplace.writelines(record_string)
                record_bigplace.writelines('\n')
