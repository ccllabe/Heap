import os
import json
import shutil
import time
import sys
import glob
import traceback
from img_mvframe_segmentation.cutting import all_cut

#path = r"C:\xampp\htdocs\lab_computing"
path = "/home/ACCOUNT/P082/webserver/lab_computing"
#save_path = r"C:\xampp\htdocs\user"
save_path = "/home/ACCOUNT/P082/webserver/user"
#err_save_path = r"C:\xampp\htdocs\lab_error"
err_save_path = "/home/ACCOUNT/P082/webserver/lab_error"

def cron_computing(path, save_path):
	pfolderlist = os.listdir(path)
	pfolderlist.sort()
	for prepare_folder in pfolderlist:
		print(prepare_folder)
		#prepare folder path
		pfolder_path = os.path.join(path, prepare_folder)
		input_file = os.path.join(pfolder_path,"input.json")
		state_file = os.path.join(path,"running.json")
		s_array = prepare_folder.split("_")
		if '' in s_array:
			shutil.rmtree(pfolder_path)
			break
		sfolder_path = os.path.join(save_path,s_array[2],s_array[3],"Lab",s_array[0])
		if not os.path.isdir(sfolder_path):
			shutil.rmtree(pfolder_path)
			break
		lab_description = os.path.join(sfolder_path,"description.json")
		#decide if can computing file and use chosed tool
		if os.path.isfile(state_file):
			break
		else:
			content_array = {}
			try:
				if os.path.isfile(input_file):
					content_array = {"start": time.time(),"cut_finish": -1,"detect_finish": -1}
					array_jswrite(content_array,state_file,'w')
					#read description.json
					f = open(lab_description,'r')
					js_content = f.read()
					f.close()
					ar_content = json.loads(js_content)
					#print(ar_content)
					#check handle object exist
					if ((ar_content["image id"] == "")|(ar_content["parasitic eggs kind"] == "")|(ar_content["Tool"] == "")):
						shutil.rmtree(pfolder_path)
						shutil.rmtree(sfolder_path)
						os.remove(state_file)
						break
					img_folder_path = os.path.join(save_path,s_array[2],s_array[3],"Image",ar_content["image id"])
					if not os.path.isdir(img_folder_path):
						shutil.rmtree(pfolder_path)
						shutil.rmtree(sfolder_path)
						os.remove(state_file)
						break
					#decide use which tool
					if (ar_content["Tool"] == "ssd_fordel"):
						tool_ssd_fordel(prepare_folder,content_array,ar_content)
					elif (ar_content["Tool"] == "u_net"):
						tool_u_net(prepare_folder,content_array,ar_content)
					elif (ar_content["Tool"] == "multi_fast_rcnn"):
						tool_multi_fast_rcnn(prepare_folder,content_array,ar_content,"1")
					elif (ar_content["Tool"] == "multi_fast_rcnn_2"):
						tool_multi_fast_rcnn(prepare_folder,content_array,ar_content,"2")
					else:
						shutil.rmtree(pfolder_path)
						shutil.rmtree(sfolder_path)
						os.remove(state_file)
					break
			except Exception as e:
				print(e)
				content_array["err_msg"]=traceback.format_exc()
				array_jswrite(content_array,state_file,'w')
				err_folder = os.path.join(err_save_path,prepare_folder)
				err_tfile = os.path.join(err_folder,"running.json")
				err_s_folder = os.path.join(err_folder,s_array[0])
				os.rename(pfolder_path,err_folder)
				os.rename(state_file,err_tfile)
				os.rename(sfolder_path,err_s_folder)
				break

#call ssd_fordel to run
def tool_ssd_fordel(prepare_folder,content_array,ar_content):
	#add ssd_fordel folder
	#ssd_fordel_folder_path =r'C:\xampp\htdocs\Parasite_egg_identification_version_1_1\computing_node\img_parasite_egg_detection\ssd_fordel'
	ssd_fordel_folder_path = '/home/ACCOUNT/P082/computing_node/img_parasite_egg_detection/ssd_fordel'
	sys.path.insert(1, ssd_fordel_folder_path)
	from ssd_detect import ssd_run
	#path record
	#prepare folder path
	pfolder_path = os.path.join(path, prepare_folder)
	state_file = os.path.join(path,"running.json")
	#user project folder
	s_array = prepare_folder.split("_")
	sfolder_path = os.path.join(save_path,s_array[2],s_array[3],"Lab",s_array[0])
	#use img file
	img_folder_path = os.path.join(save_path,s_array[2],s_array[3],"Image",ar_content["image id"])
	img_file_array = glob.glob(img_folder_path+'/img.*')
	#use training checkpoints
	traing_model_file = os.path.join(ssd_fordel_folder_path,"checkpoints")
	kind_link_model = os.path.join(ssd_fordel_folder_path,"kind_link_model.json")
	#save folder path
	lab_description = os.path.join(sfolder_path,"description.json")
	output_folder = os.path.join(sfolder_path,"mvcut_imgs")
	output_tplace = os.path.join(sfolder_path,"place.txt")
	output_tfile = os.path.join(sfolder_path,"running.json")

	#call img_mvframe_segmentation cut img to lab_id/mvcut_imgs
	#get image img format
	img_path_array = img_file_array[0].split(".")
	jpeg_fm = ["jpg","jpeg","JPG"]
	if img_path_array[-1] in jpeg_fm:
		img_path_array[-1] = "JPEG"
	img_restrict = ["JPEG","png","PNG","bmp","BMP"]
	if img_path_array[-1] in img_restrict:
		if not os.path.isdir(output_folder):
			os.umask(0)
			os.mkdir(output_folder)
		ori_w, ori_h = all_cut(img_file_array[0], 150, 50,300, 300, 10, img_path_array[-1], output_folder)
		content_array["cut_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		#call ssd_fordel count place.txt
		#get weight path by kind
		parasitic_eggs_kind = ar_content["parasitic eggs kind"]
		f = open(kind_link_model,'r')
		klm_content = f.read()
		f.close()
		klm_array = json.loads(klm_content)
		weight_path = os.path.join(traing_model_file,klm_array[parasitic_eggs_kind])
		ssd_run(weight_path, output_folder, output_tplace, ori_w, ori_h)
		content_array["detect_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		os.rename(state_file,output_tfile)
		shutil.rmtree(pfolder_path)
	else:
		shutil.rmtree(pfolder_path)
		shutil.rmtree(sfolder_path)
		shutil.rmtree(img_folder_path)
		os.remove(state_file)

#call u_net to run
def tool_u_net(prepare_folder,content_array,ar_content):
	#add u_net folder
	#u_net_folder_path =r'C:\xampp\htdocs\Parasite_egg_identification_version_1_1\computing_node\img_parasite_egg_detection\u_net'
	u_net_folder_path = '/home/ACCOUNT/P082/computing_node/img_parasite_egg_detection/u_net'
	sys.path.insert(0, u_net_folder_path)
	from u_net_detect import u_net_run
	#path record
	#prepare folder path
	pfolder_path = os.path.join(path, prepare_folder)
	state_file = os.path.join(path,"running.json")
	#user project folder
	s_array = prepare_folder.split("_")
	sfolder_path = os.path.join(save_path,s_array[2],s_array[3],"Lab",s_array[0])
	#use img file
	img_folder_path = os.path.join(save_path,s_array[2],s_array[3],"Image",ar_content["image id"])
	print(img_folder_path)
	img_file_array = glob.glob(img_folder_path+'/img.*')
	#use training checkpoints
	traing_model_file = os.path.join(u_net_folder_path,"checkpoints")
	kind_link_model = os.path.join(u_net_folder_path,"kind_link_model.json")
	#save folder path
	lab_description = os.path.join(sfolder_path,"description.json")
	output_folder = os.path.join(sfolder_path,"mvcut_imgs")
	output_tplace = os.path.join(sfolder_path,"place.txt")
	output_tfile = os.path.join(sfolder_path,"running.json")

	#call img_mvframe_segmentation cut img to lab_id/mvcut_imgs
	#get image img format
	#print(img_file_array)
	#return
	img_path_array = img_file_array[0].split(".")
	jpeg_fm = ["jpg","jpeg","JPG"]
	if img_path_array[-1] in jpeg_fm:
		img_path_array[-1] = "JPEG"
	img_restrict = ["JPEG","png","PNG","bmp","BMP"]
	if img_path_array[-1] in img_restrict:
		if not os.path.isdir(output_folder):
			os.umask(0)
			os.mkdir(output_folder)
		ori_w, ori_h = all_cut(img_file_array[0], 150, 50,512, 512, 10, img_path_array[-1], output_folder)
		content_array["cut_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		#call ssd_fordel count place.txt
		#get weight path by kind
		parasitic_eggs_kind = ar_content["parasitic eggs kind"]
		f = open(kind_link_model,'r')
		klm_content = f.read()
		f.close()
		klm_array = json.loads(klm_content)
		weight_path = os.path.join(traing_model_file,klm_array[parasitic_eggs_kind])
		u_net_run(weight_path, output_folder, output_tplace, ori_w, ori_h)
		content_array["detect_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		os.rename(state_file,output_tfile)
		shutil.rmtree(pfolder_path)
	else:
		shutil.rmtree(pfolder_path)
		shutil.rmtree(sfolder_path)
		shutil.rmtree(img_folder_path)
		os.remove(state_file)

#call multi_fast_rcnn to run
def tool_multi_fast_rcnn(prepare_folder,content_array,ar_content,version_n):
    #add multi_fast_rcnn folder
    #multi_fast_rcnn_folder_path =r'C:\xampp\htdocs\Parasite_egg_identification_version_1_3\computing_node\img_parasite_egg_detection\multi_fast_rcnn'
	multi_fast_rcnn_folder_path = '/home/ACCOUNT/P082/computing_node/img_parasite_egg_detection/multi_fast_rcnn'
	sys.path.insert(0, multi_fast_rcnn_folder_path)
	from multi_fast_rcnn_detect import multi_fast_rcnn_run
	#path record
	#prepare folder path
	pfolder_path = os.path.join(path, prepare_folder)
	state_file = os.path.join(path,"running.json")
	#user project folder
	s_array = prepare_folder.split("_")
	sfolder_path = os.path.join(save_path,s_array[2],s_array[3],"Lab",s_array[0])
	#use img file
	img_folder_path = os.path.join(save_path,s_array[2],s_array[3],"Image",ar_content["image id"])
	img_file_array = glob.glob(img_folder_path+r'/img.*')
	#use training checkpoints
	traing_model_file = os.path.join(multi_fast_rcnn_folder_path,"checkpoints")
	#kind_link_model = os.path.join(multi_fast_rcnn_folder_path,"kind_link_model.json")
	#save folder path
	lab_description = os.path.join(sfolder_path,"description.json")
	output_folder = os.path.join(sfolder_path,"mvcut_imgs")
	output_tplace = os.path.join(sfolder_path,"place.txt")
	output_tfile = os.path.join(sfolder_path,"running.json")

	#call img_mvframe_segmentation cut img to lab_id/mvcut_imgs
	#get image img format
	img_path_array = img_file_array[0].split(".")
	#os.mkdir(output_folder)
	jpeg_fm = ["jpg","jpeg","JPG"]
	if img_path_array[-1] in jpeg_fm:
		img_path_array[-1] = "JPEG"
	img_restrict = ["JPEG","png","PNG","bmp","BMP"]
	if img_path_array[-1] in img_restrict:
		if not os.path.isdir(output_folder):
			os.umask(0)
			os.mkdir(output_folder)
		ori_w, ori_h = all_cut(img_file_array[0], 150, 50,300, 300, 10, img_path_array[1], output_folder)
		content_array["cut_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		#call multi_fast_rcnn count place.txt
		#get weight path by kind
		parasitic_eggs_kind = ar_content["parasitic eggs kind"]
		weight_path = os.path.join(traing_model_file,"version"+version_n)
		multi_fast_rcnn_run(weight_path, "vgg16", parasitic_eggs_kind, output_folder, output_tplace, ori_w, ori_h)
		content_array["detect_finish"]=time.time()
		array_jswrite(content_array,state_file,'w')
		os.rename(state_file,output_tfile)
		shutil.rmtree(pfolder_path)
	else:
		shutil.rmtree(pfolder_path)
		shutil.rmtree(sfolder_path)
		shutil.rmtree(img_folder_path)
		os.remove(state_file)

def array_jswrite(w_array,dst,w_mode):
    js_content = json.dumps(w_array)
    f = open(dst,w_mode)
    f.write(js_content)
    f.close()



if __name__ == '__main__':
    cron_computing(path, save_path)
    print(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime()) + ":\tDetection")
