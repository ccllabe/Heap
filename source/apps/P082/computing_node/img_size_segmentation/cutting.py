from PIL import Image
Image.MAX_IMAGE_PIXELS = 100000000000
#Image.warnings.simplefilter('error', Image.DecompressionBombWarning)

#cut img with fix size
def fix_size_segmentation(src, cut_w, cut_h, img_format, dstpath, thumbnail_size, dstpath2):
    img = Image.open(src)
    img_w, img_h = img.size
    img = decide_overlay(img, img_w, img_h, cut_w, cut_h)
    img_w, img_h = img.size
    count = 0
    for i in range(0,img_w,cut_w):
        for j in range(0,img_h,cut_h):
            box = (i, j, i+cut_w, j+cut_h)
            path = '{:s}/{:s}.{:d}.{:d}.{:d}.{:s}'.format(dstpath,'target',count,i,j,img_format)
            img.crop(box).save(path, img_format)
            count+=1
    img.thumbnail(thumbnail_size, Image.ANTIALIAS)
    img.save(dstpath2, "png")

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
