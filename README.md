## Progetto 'Bool-BnB' 

# Introduzione

BoolBnB è una applicazione per trovare e gestire l&#39;affitto di appartamenti.

Attraverso BoolBnB i proprietari di appartamento possono inserire le informazioni degli appartamenti che vogliono affittare per cercare utenti interessati.

Gli utenti che vogliono mettere in affitto un appartamento devono registrarsi alla piattaforma; una volta registrati hanno la possibilità di inserire uno o più appartamenti.

Gli utenti interessati ad un appartamento, utilizzando i filtri di una apposita pagina di ricerca, vedono una lista di possibili appartamenti e cliccando su ognuno possono vedere una pagina di dettaglio.

Una volta trovata l&#39;appartamento desiderato, l&#39;utente interessato può contattare l&#39;utente proprietario per fare domande.

Inoltre, i proprietari di un appartamento possono decidere di pagare per sponsorizzare l&#39;annuncio del proprio appartamento per fare in modo che il loro annuncio sia maggiormente in evidenza rispetto a quelli non sponsorizzati.

Tipi di Utenti

Definiamo i seguenti tipi di utente che possono utilizzare BoolBnB:

- Utente proprietario registrato (UPR): un utente proprietario che ha effettuato la registrazione
- Utente proprietario registrato con appartamento (UPRA): un utente che ha effettuato la registrazione e ha inserito nel sistema almeno una appartamento
- Utente interessato (UI): un qualsiasi utente del sito, non registrato

Requisiti Visivi

Look and Feel

L'aspetto estetico di BoolBnB deve essere ispirato al sito [www.airbnb.com](http://www.airbnb.com/)

Non deve essere una copia ma una fonte di ispirazione da cui prendere spunto per quanto riguarda colori, font, elementi di interazione etc

Lista delle pagine minime

La seguente lista è una lista non completa delle pagine necessarie al funzionamento dell&#39;applicazione con i relativi mockup.

I mockup rappresentano una possibile presentazione dei dati e non sono l&#39;unico modo con cui possono essere rappresentati.

Qui il link: https://docs.google.com/presentation/d/19ox-mnz\_KIZS9P1K7MPbEoVrnjH6FnLH0-oH0NRgzN0/edit#slide=id.g27fb29ff30898801\_127

- ***** Homepage(Mockup1):** ffre la possibilità di ricercare appartamenti nel database. Inoltre permette un accesso veloce alle pagine dettaglio degli appartamenti in evidenza
- ***** Pagina di Ricerca(Mockup2):** permette di visualizzare i risultati di ricerca, ogni risultato permetterà l&#39;accesso alla pagina di dettaglio di dell&#39;appartamento. Inoltre è possibile raffinare la ricerca modificando tutti i parametri di ricerca
- ****** Pagine Dettaglio Appartamento(Mockup3):** permette di visualizzare tutti i dettagli disponibili per un appartamento. Inoltre è possibile scrivere un messaggio al proprietario dell&#39;appartamento
- ****** Pagina Statistiche Appartamento(Mockup4):** permette di visualizzare le statistiche sugli appartamenti messi in affitto, una pagina per appartamento.
- ****** Pagine Sponsorizzazione(Mockup5):** tramite questo pannello è possibile sponsorizzare un singolo appartamento, selezionando la promozione e inserendo i dettagli della carta di credito.

Requisiti Tecnici

- ****** (RT1) Client-side Validation:** tutti gli input inseriti dell&#39;utente devono essere controllati client-side (oltre che server-side) per un controllo di veridicità (es. un numero di stanze deve essere positivo)
- ****** (RT2) Salvataggio informazioni di geografiche:** i dati riguardanti l&#39;ubicazione degli appartamenti devono essere salvati sul database con latitudine e longitudine. Per ottenere latitudine e longitudine a partire da un indirizzo e allo stesso modo visualizzare il punto sulla mappa, utilizzare tomtom: [https://developer.tomtom.com/](https://developer.tomtom.com/)
- ****** (RT3) Sistema di Pagamento:** il sistema di pagamento da utilizzare è braintree ([https://www.braintreepayments.com/](https://www.braintreepayments.com/) ). Il sistema permette di simulare pagamenti senza essere approvati formalmente e senza utilizzare vere carte di credito.
- ****** (RT4) Il sito deve essere **** responsive ****:** il sito deve essere correttamente visibile da desktop e da smartphone

Requisiti Funzionali

Nel dettaglio, la piattaforma deve soddisfare i seguenti requisiti funzionali (RF) che vengono dettagliati nelle pagine successive:

- (RF1) Permettere ai proprietari di appartamento di registrarti alla piattaforma
- (RF2) Permettere ai proprietari di appartamento registrati di aggiungere un appartamento alla piattaforma
- (RF3) Permette ai visitatori di ricercare una appartamento
- (RF4) Permettere ai visitatori di vedere i dettagli di un appartamento
- (RF5) Permettere ai visitatori di scrivere al proprietario di un appartamento per chiedere informazioni
- (RF6) Permettere ai proprietari di appartamento registrati di vedere le richieste ricevute
- (RF7) Permettere ai proprietari di appartamento registrati di vedere statistiche riguardo gli annunci dei propri appartamenti
- (RF8) Permettere ai proprietari di appartamento registrati di sponsorizzare il propria appartamento

(RF1) Permettere ai proprietari di appartamento di registrarsi alla piattaforma

**Visibilità:** UI

**Descrizione:** L&#39;applicazione deve permettere ai proprietari di appartamento di registrarsi alla piattaforma e creare un loro profilo.

Le informazioni che l&#39;utente può inserire sono:

- Email \*
- Password \*
- Nome
- Cognome
- Data di Nascita

Sono contrassegnati con \* i dati obbligatori.

Email e password sono utilizzati dall&#39;utente per fare login alla piattaforma.

Non è previsto un pannello per modificare le informazioni inserite una volta registrato.

I form devono rispettare RT1

**Risultato:** Un nuovo utente viene creato nel sistema

**Eccezioni:** Esiste già nel sistema un utente con l&#39;email inserita

(RF2) Permettere ai proprietari di appartamento registrati di aggiungere una appartamento alla piattaforma

**Visibilità:** UPR / UPRA

**Descrizione:** Un proprietario registrato ha la possibilità di inserire uno o più appartamenti all&#39;interno del sistema.

Per inserire un nuovo appartamento il proprietario deve inserire le seguenti informazioni:

- Titolo riepilogativo che descriva l&#39;appartamento
- Numero di stanze
- Numero di posti letto
- Numero di bagni
- Metri quadrati
- Indirizzo
- Immagine rappresentativa dell&#39;appartamento

e la presenza di uno o più dei seguenti servizi aggiuntivi (WiFi, Posto Macchina, Piscina, Portineria, Sauna, Vista Mare).

Deve essere possibile modificare le informazioni inserite e disattivare temporaneamente un annuncio, rendendolo non visibile nei risultati di ricerca

I form devono rispettare RT1.

L&#39;inserimento dell&#39;indirizzo deve portare al salvataggio sul database di latitudine e longitudine come descritto in RT2.

**Risultato:** Una stanza è inserita nel sistema e le sue informazioni sono aggiornate

**Eccezioni: /**

(RF3) Permette ai visitatori di ricercare un appartamento

**Visibilità:** UI / UPR / UPRA

**Descrizione:** Un qualsiasi utente deve essere in grado di ricercare un appartamento all&#39;interno del database.

Inserendo latitudine e longitudine, il sistema ricerca all&#39;interno del database gli appartamenti nel raggio di 20 km dalla latitudine e longitudine indicata.

Inoltre è possibile raffinare ulteriormente la ricerca impostando uno o più dei seguenti filtri:

- Numero minimo di stanze
- Numero minimo di posti letto
- Modificare il raggio di default di 20km
- La presenza obbligatoria di uno o più dei servizi aggiuntivi indicati in RF2

I risultati vengono ordinati per distanza dalla latitudine/longitudine inserita.

**Risultato:** Viene generata una lista di appartamenti che corrispondono alla ricerca che mostra alcuni dettagli della stanza

**Eccezioni: /**

(RF4) Permettere ai visitatori di vedere i dettagli di un appartamento

**Visibilità:** UI / UPR / UPRA

**Descrizione:** Selezionando un appartamento dall&#39;apposito pannello devono apparire tutti i dettagli disponibili riguardanti la stanza, come specificato in RF2.

In particolare, deve essere essere presenta una mappa che indica la posizione dell&#39;appartamento.

**Risultato:** Viene visualizzata la pagina di dettaglio di un appartamento

**Eccezioni: /**



(RF5) Permettere ai visitatori di scrivere al proprietario di un appartamento per chiedere informazioni

**Visibilità:** UI / UPR / UPRA

**Descrizione:** Nella pagina di dettaglio dell&#39;appartamento deve essere possibile inviare un messaggio al proprietario dell&#39;appartamento.

L&#39;utente deve inserire la propria email e un messaggio.

Nel caso in cui l&#39;utente sia un UPR o UPRA registrato, l&#39;email deve essere autocompletato con quella inserita durante la registrazione

**Risultato:** Il messaggio viene salvato nel database

**Eccezioni: /**

(RF6) Permettere ai proprietari di appartamento registrati di vedere le richieste ricevute

**Visibilità:** UPRA

**Descrizione:** Un proprietario che ha inserito uno o più appartamenti deve avere la possibilità di vedere i messaggi di richiesta ricevuti dagli utenti per gli appartamenti.

In questa schermata dovrà vedere email e messaggio inserito dall&#39;utente per ogni messaggio ricevuto.

Non è prevista la possibilità di risposta da parte dell&#39;utente UPRA (che risponderà direttamente via email fuori dalla piattaforma)

**Risultato:**

**Eccezioni: /  **

(RF7) Permettere ai proprietari di appartamento registrati di vedere statistiche riguardo gli annunci dei propri appartamenti

**Visibilità:** UPRA

**Descrizione:** Un proprietario che ha inserito uno o più appartamenti deve avere la possibilità di vedere le statistiche di visualizzazione per ogni appartamento inserito. L&#39;utente UPRA dovrà vedere la lista dei suoi appartamenti e, selezionandone uno, dovrà vedere le statistiche associate come illustrato in _Mockup4_

**Risultato:** L&#39;utente visualizza le statistiche dell&#39;appartamento selezionato

**Eccezioni: /**

(RF8) Permettere ai proprietari di appartamento registrati di sponsorizzare il propria appartamento

**Visibilità:** UPRA

**Descrizione:** Un proprietario che ha inserito uno o più appartamenti deve avere la possibilità di pagare per mettere in risalto un loro annuncio.

Entrando in un pannello apposito della sua sezione personale, l&#39;utente UPRA selezionerà uno dei suoi appartamenti e dovrà scegliere uno dei seguenti pacchetti promozionali:

- 2,99 € per 24 ore di sponsorizzazione
- 99 € per 72 ore di sponsorizzazione
- 99 € per 144 ore di sponsorizzazione

Il pagamento deve avvenire tramite carta di credito seguendo RT3

Un appartamento sponsorizzato ha le seguenti particolarità:

- Appare in Homepage nella sezione &quot;Appartamenti in Evidenza&quot;
- Viene posizionato sempre in alto nella pagina di ricerca, con uno sfondo diverso, a prescindere dalla distanza dalla latitudine e longitudine inserita

Terminato il periodo di sponsorizzazione, l&#39;appartamento tornerà ad essere visualizzato normalmente, senza alcuna particolarità.

**Risultato:** L&#39;appartamento viene sponsorizzato

**Eccezioni:** Il sistema di pagamento non ha processato correttamente il pagamento / i dati della carta di credito non sono valida

Consigli del CTO

1. Tecnologie da utilizzare: non c&#39;è alcun limite nelle tecnologie utilizzabili, purchè rispettino i requisiti. Chiaramente la scelte più ovvia (e consigliata) è quella di fare tutto con Laravel.
2. Non iniziare a scrivere subito codice; è importante leggere e rileggere bene il documento per avere un&#39;idea chiara del progetto
3. I RF sono in ordine di complessità, seguendo l&#39;ordine si costruisce un&#39;applicazione funzionante con funzionalità crescenti. E&#39; importante però avere bene in mente la strada da seguire per non fare scelte iniziali che andranno totalmente cambiate successivamente
4. E&#39; possibile dividersi i compiti per procedere speditamente, soprattutto all&#39;inizio. Ad esempio, qualcuno potrebbe occuparsi solo della parte estetica, ignorando tutto il resto. Un altro potrebbe pensare solamente al database, sistemando Model e Migrations. Un altro potrebbe occuparsi di capire come funziona il sistema di pagamento, etc...
5. La parte più nuova è appunto quella che riguarda il processo di pagamento: non richiede alcuna nuova tecnologia ma è un processo fatto da diversi concetti da comprendere quindi richiede la lettura approfondita della documentazione. É presente una funzionalità Laravel che gestisce i pagamenti e si integra anche con Braintree ([https://laravel.com/docs/6.x/billing](https://laravel.com/docs/6.x/billing)). Laravel però suggerisce che, in caso di pagamenti singoli, è meglio integrarsi direttamente con l&#39;SDK di Braintree
6. Per soddisfare il fatto che non si deve avere un refresh della pagina nel RF2 bisogna fare chiamate ajax. Il metodo più utilizzato in questi casi è fare in modo che ogni cambiamento di agli input vada a modificare i campi utili alla ricerca nell&#39;url es (/ricerca?lat=45.34&amp;lng=9.30&amp;stanze=2&amp;radius=20). Dopo ogni cambiamento viene fatta una chiamata ajax con i parametri presenti nella URL.
