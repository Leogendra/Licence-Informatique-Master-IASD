import sys, os

os.system("clear")

def parcours(rep):
  print(rep)
  liste=os.listdir(rep)
  for fichier in liste:
    cheminNom=os.path.join(rep,fichier)
    if os.path.isdir(os.path.join(rep, fichier)) and os.acces(fichier, os.X_OK):
      parcours(cheminNom)
    else:
      pass
      #print(fichier, "est un fichier régulier (pas un dossier)")

parcours(sys.argv[1])