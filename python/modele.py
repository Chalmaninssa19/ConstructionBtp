import string
import math
import numpy as np
import pandas as pd
import tfidf as tfidf
import psycopg2

#Charger le dataframe venant du csv
def load_df() :
    # Charger le fichier CSV dans un DataFrame
    df = pd.read_csv("/home/chalman/Documents/cours/tsinjo/analyse de donnees/tp/emailSpam/datas.csv", sep=";")

    # Remplacer "spam" par 1 et "isSpam" par 0 dans la colonne A
    df['isSpam'] = df['isSpam'].replace({'spam': 1, 'non-spam': 0})
    
    return df

def convert_df_in_matrix(df) :
    return tfidf.complete_matrix(df)

# Pour entrainer le modèle
def train_modele(data):
    
    # Attributs et classification
    X = data[:, 1:]
    y = data[:, 0]
    
    
    # division donnees de test et entrainement
    from sklearn.model_selection import train_test_split
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size = 0.20, random_state = 0)
    
    # Entrainement d'une regression logistique
    from sklearn.linear_model import LogisticRegression
    modele = LogisticRegression()
    modele = modele.fit(X_train, y_train)
    
    
    # Prediction des donnees de test
    y_pred = modele.predict(X_test)
    
    # Resultat du prédiction
    from sklearn.metrics import confusion_matrix
    cm = confusion_matrix(y_test, y_pred)
    
    # Précision
    from sklearn.metrics import accuracy_score
    
    return modele

#enregistrer le model
def save_model(modele) :    
    from joblib import dump
    dump(modele,'/home/chalman/Documents/cours/tsinjo/analyse de donnees/tp/email/python/model_saved.joblib')


#Charger le model
def load_model() :   
    from joblib import load

    return load('/home/chalman/Documents/cours/tsinjo/analyse de donnees/tp/email/python/model_saved.joblib')

#Recuperer les nouveaux donnees
def getNewDatas() :
    # Paramètres de connexion à la base de données PostgreSQL
    db_params = {
        "host": "localhost",
        "database": "email",
        "user": "postgres",
        "password": "root"
    }

    try:
        # Établir une connexion à la base de données PostgreSQL
        connection = psycopg2.connect(**db_params)

        # Créer un objet "curseur" pour exécuter des requêtes SQL
        cursor = connection.cursor()

        # Exécuter une requête SQL pour récupérer les données
        cursor.execute("SELECT isspam, sujet FROM new_donnee;")

        # Récupérer les résultats de la requête dans une liste de tuples
        rows = cursor.fetchall()

        # Fermer le curseur et la connexion
        cursor.close()
        connection.close()

        # Convertir la liste de tuples en DataFrame
        df = pd.DataFrame(rows, columns=['isSpam', 'sujet'])  # Remplacez les noms de colonnes par les vôtres

        return df

    except psycopg2.Error as e:
        print("Erreur lors de la connexion à la base de données PostgreSQL:", e)

#Preparation des donnees a mettre a jour
def prepare_datas_to_update() :
    newDatas = getNewDatas()
    df = load_df()
    # Concaténer les deux DataFrames
    df_update = pd.concat([df, newDatas], ignore_index=True)

    return df_update

#Traitement et sauvegarde du model
def trait_and_save_model(df) :
    matrix = convert_df_in_matrix(df)
    modele = train_modele(matrix)
    save_model(modele)