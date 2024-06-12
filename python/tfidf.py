import string
import math
import numpy as np
import pandas as pd

# Supprime "l'" du mot et retourne le mot sans "l'"
def remove_l_apostrophe(word):
    word = word.lower()
    if "l'" in word:
        return word.replace("l'", "")
    else:
        return word

#Listes des conjonctions
def list_conjonctions() :
    return ["mais", "ou", "et", "donc", "or", "ni", "car"]

#Supprimer le conjonction
def remove_conjonction(word) :
    word = word.lower()
    conjonctions = list_conjonctions()
    if word in conjonctions :
        return word.replace(word, "")
    else:
        return word
    
#Supprimer les l' du document
def remove_all_l_apostrophe(document) :
    new_doc = ""
    for word in document.split() :
        new_doc += remove_l_apostrophe(word)+" "
    return new_doc

#supprimer toutes les conjonctions dans le doc
def remove_conjonctions(document) :
    new_doc = ""
    for word in document.split() :
        new_doc += remove_conjonction(word)+" "
    return new_doc

# Supprime les caractères spéciaux, la ponctuation et les symboles indésirables
def remove_special_characters(document):
    cleaned_document = ""
    for char in document:
        if char not in string.punctuation and char not in string.digits:
            cleaned_document += char
    return cleaned_document

#Avoir les vocabulaires dans un document
def getVocabulary(doc) :
    document_without_l_apostrophe = remove_all_l_apostrophe(doc)
    document_without_characters = remove_special_characters(document_without_l_apostrophe)
    document_without_conjonctions = remove_conjonctions(document_without_characters)
    
    return document_without_conjonctions.split()

#Calculer le nombre de repetition du mot dans le vocabulaire
def count_nb_reptition_word(word, vocs) :
    count = 0
    for item in vocs :
        if word == item :
            count += 1
    return count

#Cacul du term frequency
def calcul_tf(word, vocs, nb_word) :
    nb_repetition_word = count_nb_reptition_word(word, vocs)
    
    return nb_repetition_word / nb_word

#Nombre de document contenant le mot
def nb_document_with_word(docs, word) :
    n_doc = 0
    for doc in docs.values :
        vocs = getVocabulary(doc[1])
        if word in vocs :
            n_doc += 1
    return n_doc

#calcul du invers document frequency
def calcul_idf(docs, word) :
    #n_doc = len(docs)
    n_doc = docs.shape[0]
    n_doc_with_word = nb_document_with_word(docs, word)
    freq = n_doc / n_doc_with_word
    
    return math.log(freq)

#La valeur du tf-idf
def calcul_tfIdf(docs, doc, word) :
    word = word.lower()
    idf = calcul_idf(docs, word)
    tf = calcul_tf(word, getVocabulary(doc), len(doc.split()))
    
    return idf*tf

#Completer les colonnes par les tf-idfs
def complete_matrix(docs) :
    rows = docs.shape[0]
    cols = 50
    matrix = np.zeros((rows, cols))
    r = 0
    for doc in docs.values :
        matrix[r, 0] = doc[0]
        vocs = getVocabulary(doc[1])
        c = 1
        for voc in vocs :
            matrix[r, c] = calcul_tfIdf(docs, doc[1], voc)
            c += 1
        r += 1
    
    return matrix 

#Creation d'une matrice par l'email
def create_matrix(docs, email) :
    rows = 1
    cols = 49
    matrix = np.zeros((rows, cols))
    vocs = getVocabulary(email)
    c = 0
    for voc in vocs :
        matrix[0, c] = calcul_tfIdf(docs, email, voc)
        c += 1
        
    return matrix