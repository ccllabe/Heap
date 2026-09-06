import tensorflow as tf
import numpy as np
import os
import sys
import cv2
import matplotlib.pyplot as plt
import pickle
import gc
import time
#os.environ["CUDA_VISIBLE_DEVICES"] = "-1"
from keras.applications.imagenet_utils import preprocess_input
from keras.backend.tensorflow_backend import set_session
from keras.models import Model
from keras.preprocessing import image
from scipy.misc import imread
from ssd import SSD300
from ssd_utils import BBoxUtility
from os.path import basename

def ssd_run(weight_path, img_path, place_record_path, ori_w, ori_h):
	plt.rcParams['figure.figsize'] = (8, 8)
	plt.rcParams['image.interpolation'] = 'nearest'

	np.set_printoptions(suppress=True)

	voc_classes = ['Parasitic_egg']
	NUM_CLASSES = len(voc_classes) + 1

	input_shape=(300, 300, 3)
	model = SSD300(input_shape, num_classes=NUM_CLASSES)
	model.load_weights(weight_path, by_name=True)
	model.compile('sgd','mse')
	bbox_util = BBoxUtility(NUM_CLASSES)

	inputs = []
	images = []
	filelist = os.listdir(img_path)
	for file in filelist:
		oldDir = os.path.join(img_path, file)
		img = image.load_img(oldDir, target_size=(300,300))
		img = image.img_to_array(img)
		images.append(imread(oldDir))
		inputs.append(img.copy())
	inputs = preprocess_input(np.array(inputs))

	preds = model.predict(inputs, batch_size=1, verbose=1)
	results = bbox_util.detection_out(preds)
	#print("predict and result time:"+str(time.time()))

	record_bigplace = open(place_record_path,"w")
	#print("Writing...\n")
	for i, img in enumerate(images):
		if results[i]!=[]:
			testi = i
			# Parse the outputs.
			det_label = results[i][:, 0]
			det_conf = results[i][:, 1]
			det_xmin = results[i][:, 2]
			det_ymin = results[i][:, 3]
			det_xmax = results[i][:, 4]
			det_ymax = results[i][:, 5]

			# Get detections with confidence higher than 0.6.
			top_indices = [i for i, conf in enumerate(det_conf) if conf >= 0.0]

			top_conf = det_conf[top_indices]
			top_label_indices = det_label[top_indices].tolist()
			top_xmin = det_xmin[top_indices]
			top_ymin = det_ymin[top_indices]
			top_xmax = det_xmax[top_indices]
			top_ymax = det_ymax[top_indices]

			colors = plt.cm.hsv(np.linspace(0, 1, 4)).tolist()

			plt.imshow(img / 255.)
			currentAxis = plt.gca()
			label_name = None
			#get file_pic
			filname = filelist[testi].split('.')
			for i in range(top_conf.shape[0]):
				xmin = int(round(top_xmin[i] * img.shape[1]))
				ymin = int(round(top_ymin[i] * img.shape[0]))
				xmax = int(round(top_xmax[i] * img.shape[1]))
				ymax = int(round(top_ymax[i] * img.shape[0]))
				score = top_conf[i]
				label = int(top_label_indices[i])
				label_name = voc_classes[label - 1]
				display_txt = '{:0.2f}, {}'.format(score, label_name)
				coords = (xmin, ymin), xmax-xmin+1, ymax-ymin+1
				color = colors[label]
				currentAxis.add_patch(plt.Rectangle(*coords, fill=False, edgecolor=color, linewidth=2))
				currentAxis.text(xmin, ymin, display_txt, bbox={'facecolor':color, 'alpha':0.5})
				#debug for xmin==xmax || ymin==ymax
				if(xmax==xmin):
					if(xmin>0):
						xmin-=1
					else:
						xmax+=1
				if(ymax==ymin):
					if(ymin>0):
						ymin-=1
					else:
						ymax+=1
				if (ori_w>(int(filname[2])+xmin)) and (ori_h>(int(filname[3])+ymin)):
					record_string = str(int(filname[2])+xmin)+','+str(int(filname[3])+ymin)+','+str(int(filname[2])+xmax)+','+str(int(filname[3])+ymax)+','+str(int(score*100.0))
					record_bigplace.writelines(record_string)
					record_bigplace.writelines('\n')
	record_bigplace.close()
	#print("Done...\n")
	gc.collect()

if __name__ == '__main__':
    weight_path = './checkpoints/Trichuris_trichiura_egg_original.weights.30-0.51.hdf5'
    imgs_path = "/home/ACCOUNT/P082/test/mvcut_imgs_300"
    place_record_path = "/home/ACCOUNT/P082/test/place.txt"
    ssd_run(weight_path, imgs_path, place_record_path,14270, 7133)
