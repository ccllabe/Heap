import os
from PIL import Image
from PIL import ImageEnhance
from PIL import ImageFilter
Image.MAX_IMAGE_PIXELS = 10000000000

#整個流程
def all_cut(src, greyrange, partrange,cut_w, cut_h, edgerange, img_format, dstpath):
    img = Image.open(src)
    ori_w, ori_h = img.size
    img = decide_overlay(img, ori_w, ori_h, cut_w, cut_h)
    img_w, img_h = img.size
    new_img = picture_handle(img)
    result = []
    #依序剪取儲存陣列
    for i in range(0,img_w, cut_w):
        for j in range(0,img_h, cut_h):
            cho_x, cho_y = way_count(new_img, i, j, img_w, img_h, cut_w, cut_h, edgerange, greyrange, partrange)
            result.append([cho_x, cho_y])
    #依陣列結果剪取儲存
    count = 0
    for x, y in result:
        box = (x, y, x+cut_w, y+cut_h)
        img.crop(box).save(os.path.join(dstpath, 'target.' + str(count) +'.'+str(x)+'.'+str(y)+ '.' + img_format), img_format)
        #new_img.crop(box).save(os.path.join(dstpath, 'target.' + str(count) +'.'+str(x)+'.'+str(y)+ '.' + img_format), img_format)
        count+=1
    return ori_w, ori_h

#if crop range larger than original img then using overlay to resize img
def decide_overlay(img, img_w, img_h, cut_w, cut_h):
    overlay = False
    b_img_w = img_w
    b_img_h = img_h
    if(img_w%cut_w!=0):
        overlay = True
        b_img_w = (int(img_w/cut_w)+1)*cut_w
    if(img_h%cut_h!=0):
        overlay = True
        b_img_h = (int(img_h/cut_h)+1)*cut_h
    if(overlay):
        box = (0, 0, img_w, img_h)
        mainimg = img.crop(box)
        img = Image.new('RGB', (b_img_w, b_img_h), (255, 255, 255))
        img.paste(mainimg, (0, 0))
    return img

#圖片預處理
def picture_handle(img):
    #對比度
    oricontrast = ImageEnhance.Contrast(img)
    contrast = 2.3
    test1 = oricontrast.enhance(contrast)
    #亮度
    oribright = ImageEnhance.Brightness(test1)
    brightness = 1.8
    test2 = oribright.enhance(brightness)
    #輪廓濾波
    test3 = test2.filter(ImageFilter.CONTOUR)
    #灰階
    test4 = test3.convert('L')
    #test5 = test4.convert('RGB')
    return test4

#邊緣雜質計算
def edge_dark(img, x1, y1, x2, y2, greyrange, partrange):
    count = 0
    way = False
    for i in range(x1,x2):
        for j in range(y1, y2):
            if img.getpixel((i, j))<=greyrange:
                count +=1
    if count > partrange:
        way = True
    return way

#單一方塊各邊雜質判定(橫軸)
def qua_side_x(img, x, y, width, high, edgerange, greyrange, partrange):
    way = [False,False]
    #左右
    way[0] = edge_dark(img, x, y, x+edgerange, y+high, greyrange, partrange)
    way[1] = edge_dark(img, x+width-edgerange, y, x+width, y+high, greyrange, partrange)
    return way

#單一方塊各邊雜質判定(縱軸)
def qua_side_y(img, x, y, width, high, edgerange, greyrange, partrange):
    way = [False,False]
    #上下
    way[0] = edge_dark(img, x, y, x+width, y+edgerange, greyrange, partrange)
    way[1] = edge_dark(img, x, y+high-edgerange, x+width, y+high, greyrange, partrange)
    return way

#移動方向計算選擇
def way_count(img, i, j, w, h, width, high, edgerange, greyrange, partrange):
    #初始計算
    cho_x = i
    cho_y = j
    #左右參數range(1/2->1/4->1/8)
    x_way_range = int(width/2)
    #上下參數range(1/2->1/4->1/8)
    y_way_range = int(high/2)
    #初始值
    way_x = qua_side_x(img, cho_x, cho_y, width, high, edgerange, greyrange, partrange)
    #先調左右
    while(way_x[0]!=way_x[1])&(x_way_range!=0):
        #左移or右移(不跑出大圖)
        if (way_x[0]==True)&(cho_x-x_way_range>0):
            cho_x=cho_x-x_way_range
        elif(way_x[0]==False)&(cho_x+x_way_range<w-width):
            cho_x=cho_x+x_way_range
        else:
            break
        #計算修正後情況
        way_x = qua_side_x(img, cho_x, cho_y, width, high, edgerange, greyrange, partrange)
        x_way_range = int(x_way_range/2)
    #初始值
    way_y = qua_side_y(img, cho_x, cho_y, width, high, edgerange, greyrange, partrange)
    #後調上下
    while(way_y[0]!=way_y[1])&(y_way_range!=0):
        #左移or右移(不跑出大圖)
        if (way_y[0]==True)&(cho_y-y_way_range>0):
            cho_y=cho_y-y_way_range
        elif(way_y[0]==False)&(cho_y+y_way_range<h-high):
            cho_y=cho_y+y_way_range
        else:
            break
        #計算修正後情況
        way_y = qua_side_y(img, cho_x, cho_y, width, high, edgerange, greyrange, partrange)
        y_way_range = int(y_way_range/2)
    return cho_x, cho_y
