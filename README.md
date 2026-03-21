## Guida Git per il Progetto BookSwap

### CONFIGURAZIONE INIZIALE (Solo la prima volta)
Da eseguire una sola volta per configurare l'identità sul computer:

git config --global user.name "username"
git config --global user.email "tua@email.it"

### PRIMO ACCESSO (Download del progetto)
Se non hai ancora la cartella sul tuo PC:

git clone https://github.com/umpbrut/bookswap.git
cd bookswap

### FLUSSO DI LAVORO QUOTIDIANO
Seguire questi passaggi ogni volta che si lavora al progetto:

1. Aggiornare il progetto locale:
git checkout vostro_cognome
git pull origin main

2. Aggiungere le modifiche fatte:
git add *

3. Creare uno snapshot (Commit):
git commit -m "Descrizione di cosa hai aggiunto o modificato"

4. Inviare le modifiche su GitHub:
git push origin vostro_cognome

NOTA BENE: Il comando push salva le modifiche sul vostro ramo personale. 
Il Leader controllerà il codice su GitHub prima di unirlo al ramo principale (MAIN).
