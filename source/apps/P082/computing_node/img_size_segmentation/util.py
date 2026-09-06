import os.path
from PIL import Image
from cutting import fix_size_segmentation

#cmd input
def input_need():
    src = input('請輸入圖片文檔路徑：')
    if os.path.isfile(src):
        dstpath = input('請輸入圖片輸出目錄（不輸入路徑則表示使用源圖片所在目錄）：')
        if (dstpath == '') or os.path.exists(dstpath):
            cut_w = int(input('矩陣寬：'))
            cut_h = int(input('矩陣長：'))
            img_format = input('儲存格式：')
            #fix_size_segmentation(src, cut_w, cut_h, img_format, dstpath)
            fix_size_segmentation(src, cut_w, cut_h, img_format, dstpath, (300,300), os.path.join(dstpath,"thumbnail.png"))
        else:
            print('圖片輸出目錄 %s 不存在！' % dstpath)
    else:
        print('圖片文檔 %s 不存在！' % src)

if __name__ == '__main__':
    input_need()
