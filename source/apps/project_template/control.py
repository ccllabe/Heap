# -*- coding: utf-8 -*-
"""
Created on Tue Dec 22 16:38:10 2020

@author: youngxian
"""

import mouse
import serial
ser = serial.Serial("COM3",9600,timeout=2)

def getchar():
    key = getch()
    key_num = ord(key)
    key_chr= chr(key_num)
    return key_num

while True:
    data_raw = ser.readline()
    data = data_raw.decode()
    print(data)
    data = data.strip()
    data.replace("\n","")
    if data == 'r':
        #right
        #mouse.move(50, 0, absolute=False, duration=0.1)
        
        # scroll down
        mouse.wheel(-0.2)
        
    elif data == 'l':
        #left
        #mouse.move(-50,0, absolute=False, duration=0.1)
        
        # scroll up
        mouse.wheel(0.2)
    data = ""
ser.close()