import os
import json
import shutil
import time
import traceback
from glob import glob
from cutting import fix_size_segmentation

#path = r"C:\xampp\htdocs\img_view_cut_computing"
path = "/home/ACCOUNT/P082/webserver/img_view_cut_computing"
#save_path = r"C:\xampp\htdocs\user"
save_path = "/home/ACCOUNT/P082/webserver/user"
#err_save_path = r"C:\xampp\htdocs\img_view_cut_error"
err_save_path = "/home/ACCOUNT/P082/webserver/img_view_cut_error"

def cron_computing(path, save_path):
	pfolderlist = os.listdir(path)
	pfolderlist.sort()
	for prepare_folder in pfolderlist:
		print(prepare_folder)
		#prepare folder path
		pfolder_path = os.path.join(path, prepare_folder)
		input_file = os.path.join(pfolder_path,"input.json")
		state_file = os.path.join(path,"running.json")
		#save folder path
		s_array = prepare_folder.split("_")
		if '' in s_array:
			shutil.rmtree(pfolder_path)
			break
		sfolder_path = os.path.join(save_path,s_array[2],s_array[3],"Image",s_array[0])
		if not os.path.isdir(sfolder_path):
			shutil.rmtree(pfolder_path)
			break
		output_folder = os.path.join(sfolder_path,"100_100_imgs")
		output_thumbnail = os.path.join(sfolder_path,"thumbnail.png")
		output_tfile = os.path.join(sfolder_path,"running.json")
		#sfolder_filelist = os.listdir(sfolder_path)
		if os.path.isfile(state_file):
			break
		else:
			content_array = {}
			try:
				if os.path.isfile(input_file):
					content_array = {"start": time.time(),"cut_finish": -1}
					array_jswrite(content_array,state_file,'w')
					#sfolder_filelist.remove('description.json')
					#img_src = os.path.join(sfolder_path,sfolder_filelist[0])
					img_src = os.path.join(sfolder_path,"img.*")
					img_src = glob(img_src)[0]
					img_path_array = img_src.split(".")
					img_restrict = ["jpg","jpeg","JPG","JPEG","png","PNG","bmp","BMP"]
					if img_path_array[-1] in img_restrict:
						os.umask(0)
						os.mkdir(output_folder)
						fix_size_segmentation(img_src, 100, 100, 'png', output_folder, (300,300), output_thumbnail)
						content_array["cut_finish"]=time.time()
						array_jswrite(content_array,state_file,'w')
						os.rename(state_file,output_tfile)
						shutil.rmtree(pfolder_path)
						break
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

def array_jswrite(w_array,dst,w_mode):
    js_content = json.dumps(w_array)
    f = open(dst,w_mode)
    f.write(js_content)
    f.close()

if __name__ == '__main__':
    cron_computing(path, save_path)
    print(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime()) + ":\tSegmentation")
