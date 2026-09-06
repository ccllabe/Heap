import sys
import os
import glob
import json
import shutil
from PIL import Image
Image.MAX_IMAGE_PIXELS = 1000000000

#host_path = r"C:\xampp\htdocs\user"
host_path = "./user"
#host_path = "/home/ACCOUNT/P082/webserver/user"
#get frame array
def txt_to_array(txt_src):
    box_array = []
    f = open(txt_src)
    for line in f:
        line=line.strip('\n')
        line=line.split(',')
        box_array.append(line)
    f.close()
    return box_array

#keep crop in img
def side_check(frame, ori_w, ori_h):
    frame[0]=int(frame[0])
    frame[1]=int(frame[1])
    side_run = not(frame[0]>ori_w or frame[1]>ori_h)
    frame[2]=[int(frame[2]),ori_w][int(frame[2])>ori_w]
    frame[3]=(int(frame[3])>ori_h and [ori_h] or [int(frame[3])])[0]
    return side_run, frame

#check folder exist(delete folder exist: kill; not target folder: create)
def fd_check(fd_dst):
	cut_run = False
	ch_dst = os.path.join(fd_dst,"delete")
	fd_dst = os.path.join(fd_dst,"target")
	if os.path.isdir(ch_dst):
		shutil.rmtree(ch_dst)
	if not os.path.isdir(fd_dst):
		os.umask(0)
		os.mkdir(fd_dst)
		cut_run = True
	return cut_run, fd_dst

#cut img in range and save
def img_cut(img_src,txt_src,fd_dst,img_ini_n,img_format):
    cut_run,fd_dst = fd_check(fd_dst)
    if cut_run:
        ori = Image.open(img_src)
        ori_w, ori_h = ori.size
        box_array = txt_to_array(txt_src)
        for i, frame in enumerate(box_array):
            side_run,frame = side_check(frame, ori_w, ori_h)
            if side_run:
                box = (frame[0], frame[1], frame[2], frame[3])
                dst = os.path.join(fd_dst, img_ini_n+'.' + str(i) +'.' + img_format)
                ori.crop(box).save(dst,img_format)
    #print("ok")
    return "ok"

#show run
def fix_input():
    img_src = r"/home/ACCOUNT/P082/webserver/user/ACCOUNT/1597370646/Image/1600070907/img.png"
    txt_src = r"/home/ACCOUNT/P082/webserver/user/ACCOUNT/1597370646/Lab/1600072784/place.txt"
    fd_dst = r"/home/ACCOUNT/P082/webserver/user/ACCOUNT/1597370646/Lab/1600072784"
    img_ini_n = "target"
    img_format = "png"
    img_cut(img_src,txt_src,fd_dst,img_ini_n,img_format)

def get_img_id(lab_path):
    json_path = os.path.join(lab_path,"description.json")
    f = open(json_path)
    json_array = json.loads(f.read())
    f.close()
    return json_array["image id"]

#for parasite_egg html
def pcall_input(u_id,p_id,l_id):
    lab_path = os.path.join(host_path,u_id,p_id,"Lab",l_id)
    #read json to know where img
    img_id = get_img_id(lab_path)
    img_fd = os.path.join(host_path,u_id,p_id,"Image",img_id)
    img_src = glob.glob(img_fd+'/img.*')[0]
    txt_src = os.path.join(lab_path,"place.txt")
    fd_dst = lab_path
    img_ini_n = "target"
    img_format = "png"
    img_cut(img_src,txt_src,fd_dst,img_ini_n,img_format)
    return "ok"

if __name__ == '__main__':
    u_id=sys.argv[1]
    p_id=sys.argv[2]
    l_id=sys.argv[3]
    state = pcall_input(u_id,p_id,l_id)
    print(state)
    #fix_input()
