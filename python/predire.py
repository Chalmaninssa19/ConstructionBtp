import tfidf as tf
import modele as md
import sys

#email = "Invitation de mariage"
email = sys.argv[1] 

df = md.prepare_datas_to_update()
# Ajouter une nouvelle ligne en utilisant la fonction loc[]
df.loc[len(df)] = [0, email]
#print(df)
matrix = tf.create_matrix(df, email) 
reg_loaded = md.load_model()
print(int(reg_loaded.predict(matrix)[0]))