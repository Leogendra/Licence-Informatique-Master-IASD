import os
import sys
import subprocess

nb_processus = sys.argv[0]
nom_fichier = "fichier.txt"
num_cle = "72"

os.system('make')
os.system('clear')
os.system('./bin/Pctrl ' + nom_fichier + ' ' + num_cle + ' &')

for p in nb_processus :
    # os.system('xfce4-terminal &')
    os.system('./bin/Pi ' + nom_fichier + ' ' + num_cle + ' &')

os.system('./bin/suppr ' + nom_fichier + ' ' + num_cle)