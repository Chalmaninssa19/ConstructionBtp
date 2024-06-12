import modele as md 

df_to_update = md.prepare_datas_to_update()
md.trait_and_save_model(df_to_update) 

print("Mise a jour du modele reussi")
