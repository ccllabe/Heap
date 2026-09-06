from cutting import all_cut

#固定輸入
def fix_input():
    #src = r'C:\Users\ACCOUNT\Desktop\Prepare_Running\test\Process_2279(10x 210mm)_1.png'
    #dstpath = r'C:\Users\ACCOUNT\Desktop\Prepare_Running\test\300x300cutpic'
    src = r'C:\Users\ACCOUNT\Desktop\Prepare_Running\Process_2279(10x 210mm)_1.png'
    dstpath = r'C:\Users\ACCOUNT\Desktop\Prepare_Running\300x300cutpic'
    greyrange = 150
    partrange = 15
    edgerange = 10
    cut_w = 300
    cut_h = 300
    img_format = 'png'
    all_cut(src, greyrange, partrange,cut_w, cut_h, edgerange, img_format, dstpath)

#輸入
def input_need():
    src = input('請輸入圖片文檔路徑：')
    if os.path.isfile(src):
        dstpath = input('請輸入圖片輸出目錄（不輸入路徑則表示使用源圖片所在目錄）：')
        if (dstpath == '') or os.path.exists(dstpath):
            greyrange = int(input('邊界背景視為空白範圍:'))
            partrange = int(input('邊界比例視為有物範圍:'))
            edgerange = int(input('邊緣範圍:'))
            high = int(input('矩陣長(>20)：'))
            width = int(input('矩陣寬(>20)：'))
            img_format = input('儲存格式：')
            img = Image.open(src)
            #取得原圖長寬
            w, h = img.size
            #原圖大於切割要求大小
            if (w-width>0)|(h-high>0):
                all_cut(img, greyrange, partrange, w, h, high, width, edgerange, img_format, dstpath)
            else:
                print('無效的行列切割參數！')
        else:
            print('圖片輸出目錄 %s 不存在！' % dstpath)
    else:
        print('圖片文檔 %s 不存在！' % src)

if __name__ == '__main__':
    fix_input()
