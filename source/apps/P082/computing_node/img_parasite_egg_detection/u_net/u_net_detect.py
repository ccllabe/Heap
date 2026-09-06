from model import *
from data import *
import gc
import os
import time

#os.environ["CUDA_VISIBLE_DEVICES"] = "-1"

def u_net_run(weight_path, imgs_path, place_record_path, ori_w, ori_h):
    datasname = readimgname(imgs_path)
    testGene = testGenerator(imgs_path,len(datasname))
    model = unet()
    model.load_weights(weight_path)
    results = model.predict_generator(testGene,len(datasname),verbose=1)
    #print("predict and result time:"+str(time.time()))
    saveResult(place_record_path,results,datasname, ori_w, ori_h)
    gc.collect()

if __name__ == '__main__':
    weight_path = './checkpoints/Trichuris_trichiura_egg_original.hdf5'
    imgs_path = '/home/ACCOUNT/P082/test/mvcut_imgs_512'
    place_record_path = "/home/ACCOUNT/P082/test/place.txt"
    u_net_run(weight_path, imgs_path, place_record_path, 14270, 7133)
