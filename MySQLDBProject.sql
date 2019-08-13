-- CREAZIONE DATABASE

CREATE DATABASE RolePlan;

-- TABELLE SENZA FOREIGN KEYS

CREATE TABLE Mondi(
     IDMondo INT PRIMARY KEY AUTO_INCREMENT,
     NomeMondo VARCHAR(20) NOT NULL
);

CREATE TABLE Archi(
     IDArco INT PRIMARY KEY AUTO_INCREMENT,
     NomeArco VARCHAR(50) NOT NULL
);

CREATE TABLE Tipi_Aree(
     IDTipoArea INT PRIMARY KEY AUTO_INCREMENT,
     NomeTipo VARCHAR(40) NOT NULL,
     Descrizione VARCHAR(400),
     EffettiAggiuntivi VARCHAR(200)
);


CREATE TABLE Tipi_Pianta(
     IDTipoPianta INT PRIMARY KEY AUTO_INCREMENT,
     Nome VARCHAR(50) NOT NULL,
     Descrizione VARCHAR(300) NOT NULL
);

CREATE TABLE Tipi_Oggetto(
     IDTipoOgg INT PRIMARY KEY AUTO_INCREMENT,
     Nome VARCHAR(80) NOT NULL,
     Peso REAL NOT NULL CHECK(Peso >= 0), /* in kgs */
     Descrizione VARCHAR(400) NOT NULL,
     CategoriaOggetto VARCHAR(20) NOT NULL CHECK(CategoriaOggetto IN ('vestiario', 'valuta', 'scritto', 'cibo', 'pozione', 'ingrediente')),
     Protezione INT CHECK(Protezione >= 0), /* user-scaled value */
     Danni INT CHECK(Danni >= 0),
     ValoreRelativo INT CHECK(ValoreRelativo >= 0)
);

CREATE TABLE Razze(
     IDRazza INT PRIMARY KEY AUTO_INCREMENT,
     NomeRazza VARCHAR(30) NOT NULL
);

CREATE TABLE Lavori(
     IDLavoro INT PRIMARY KEY AUTO_INCREMENT,
     NomeLavoro VARCHAR(30) NOT NULL
);

-- TABELLE CON FOREIGN KEYS

CREATE TABLE Aree(
     Mondo INT NOT NULL,
     IDArea INT NOT NULL,
     NomeArea VARCHAR(20) NOT NULL,
     Descrizione VARCHAR(400),
     CentroAbitato BIT NOT NULL,
     PRIMARY KEY(Mondo, IDArea),
     FOREIGN KEY(Mondo) REFERENCES Mondi(IDMondo)
          ON DELETE CASCADE
          ON UPDATE NO ACTION
);

CREATE TABLE Class_Aree(
     TipoArea INT NOT NULL,
     Mondo INT NOT NULL,
     Area INT NOT NULL,
     PRIMARY KEY(TipoArea, Mondo, Area),
     FOREIGN KEY (TipoArea) REFERENCES Tipi_Aree(IDTipoArea)
          ON DELETE CASCADE
          ON UPDATE NO ACTION,
     FOREIGN KEY(Mondo, Area) REFERENCES Aree(Mondo, IDArea)
          ON DELETE CASCADE
          ON UPDATE NO ACTION
);

CREATE TABLE Adiacenze(
     Mondo INT NOT NULL,
     Area INT NOT NULL,
     AdiacenteAdArea INT NOT NULL,
     PRIMARY KEY(Mondo, Area, AdiacenteAdArea),
     FOREIGN KEY(Mondo, AdiacenteAdArea) REFERENCES Aree(Mondo, IDArea)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Mondo, Area) REFERENCES Aree(Mondo, IDArea)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Personaggi_Giocanti(
     IDPersonaggio INT PRIMARY KEY AUTO_INCREMENT,
     NomePersonaggio VARCHAR(20) NOT NULL,
     Livello INT NOT NULL CHECK(Livello > 0),
     PuntiVitaMax INT NOT NULL CHECK(PuntiVitaMax > 0),
     PuntiVitaAtt INT NOT NULL CHECK(PuntiVitaAtt > 0 AND PuntiVitaAtt <= PuntiVitaMax),
     PuntiExp INT NOT NULL CHECK (PuntiExp > 0),
     NomeGiocatore VARCHAR(20) NOT NULL,
     MondoPresenza INT NOT NULL,
     AreaPresenza INT NOT NULL,
     Razza INT NOT NULL,
     FOREIGN KEY(MondoPresenza, AreaPresenza) REFERENCES Aree(Mondo, IDArea),
     FOREIGN KEY(Razza) REFERENCES Razze(IDRazza)
);

CREATE TABLE NPC(
     IDNPC INT PRIMARY KEY AUTO_INCREMENT,
     Nome VARCHAR(20),
     Livello INT NOT NULL CHECK(Livello > 0),
     PuntiVitaMax INT NOT NULL CHECK(PuntiVitaMax > 0),
     PuntiVitaAtt INT NOT NULL CHECK(PuntiVitaAtt > 0 AND PuntiVitaAtt <= PuntiVitaMax),
     PuntiExp INT NOT NULL CHECK (PuntiExp > 0),
     NomeGiocatore VARCHAR(20) NOT NULL,
     MondoPresenza INT NOT NULL,
     AreaPresenza INT NOT NULL,
     Razza INT NOT NULL,
     FOREIGN KEY(MondoPresenza, AreaPresenza) REFERENCES Aree(Mondo, IDArea),
     FOREIGN KEY(Razza) REFERENCES Razze(IDRazza)
);

CREATE TABLE Piante(
     IDPianta INT PRIMARY KEY AUTO_INCREMENT,
     MondoPresenza INT NOT NULL,
     AreaPresenza INT NOT NULL,
     TipoPianta INT NOT NULL,
     FOREIGN KEY(MondoPresenza, AreaPresenza) REFERENCES Aree(Mondo, IDArea)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(TipoPianta) REFERENCES Tipi_Pianta(IDTipoPianta)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Oggetti(
     IDOggetto INT PRIMARY KEY AUTO_INCREMENT,
     MondoPresenza INT NOT NULL,
     AreaPresenza INT NOT NULL,
     TipoOggetto INT NOT NULL,
     FOREIGN KEY(MondoPresenza, AreaPresenza) REFERENCES Aree(Mondo, IDArea)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
);

CREATE TABLE Decomposizioni(
     TipoOggetto INT NOT NULL,
     TipoPianta INT NOT NULL,
     PRIMARY KEY(TipoOggetto, TipoPianta),
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(TipoPianta) REFERENCES Tipi_Pianta(IDTipoPianta)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Ricette(
     IDRicetta INT PRIMARY KEY AUTO_INCREMENT,
     OggettoCreato INT NOT NULL,
     FOREIGN KEY(OggettoCreato) REFERENCES Tipi_Oggetto(IDTipoOgg)
);

CREATE TABLE Parte_Di(
     TipoOggetto INT NOT NULL,
     Ricetta INT NOT NULL,
     Quantita INT NOT NULL,
     PRIMARY KEY(TipoOggetto, Ricetta),
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Ricetta) REFERENCES Ricette(IDRicetta)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Inventari_PG(
     TipoOggetto INT NOT NULL,
     Personaggio INT NOT NULL,
     Quantita INT NOT NULL DEFAULT 1 CHECK(Quantita > 0),
     PRIMARY KEY(TipoOggetto, Personaggio),
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Personaggio) REFERENCES Personaggi_Giocanti(IDPersonaggio)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Inventari_NPC(
     TipoOggetto INT NOT NULL,
     NPC INT NOT NULL,
     Quantita INT NOT NULL DEFAULT 1 CHECK(Quantita > 0),
     PrezzoVendita INT,
     PRIMARY KEY(TipoOggetto, NPC),
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(NPC) REFERENCES NPC(IDNPC)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Interessi_Acquisto(
     TipoOggetto INT NOT NULL,
     NPC INT NOT NULL,
     PrezzoAcquisto INT NOT NULL CHECK (PrezzoAcquisto >= 0),
     PRIMARY KEY(TipoOggetto, NPC),
     FOREIGN KEY(NPC) REFERENCES NPC(IDNPC)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Occupazioni(
     Mondo INT NOT NULL,
     Area INT NOT NULL,
     NPC INT NOT NULL,
     Lavoro INT NOT NULL,
     PRIMARY KEY(Mondo, Area, NPC),
     FOREIGN KEY(Mondo, Area) REFERENCES Aree(Mondo, IDArea)
    	  ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(NPC) REFERENCES NPC(IDNPC)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Lavoro) REFERENCES Lavori(IDLavoro)
          ON UPDATE NO ACTION
          ON DELETE CASCADE     
);

CREATE TABLE Quest(
     Arco INT NOT NULL,
     NumQuest INT NOT NULL,
     RicompensaExp INT NOT NULL CHECK(RicompensaExp > 0), /* in exp points */
     Nome VARCHAR(80) NOT NULL,
     LivelloConsigliato INT NOT NULL CHECK(LivelloConsigliato > 0),
     Descrizione VARCHAR(400) NOT NULL,
     Disponibile BIT NOT NULL,
     TipoQuest VARCHAR(20) NOT NULL CHECK(TipoQuest IN ('viaggio', 'eliminazione', 'colloquio', 'raccolto')),
     NPCDialogo INT,
     NPCConsegna INT,
     MondoDestinazione INT,
     AreaDestinazione INT,
     PRIMARY KEY(Arco, NumQuest),
     FOREIGN KEY(Arco) REFERENCES Archi(IDArco)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY (NPCDialogo) REFERENCES NPC(IDNPC),
     FOREIGN KEY (NPCConsegna) REFERENCES NPC(IDNPC),
     FOREIGN KEY(MondoDestinazione, AreaDestinazione) REFERENCES Aree(Mondo, IDArea)
);

CREATE TABLE Completamenti(
     Arco INT NOT NULL,
     Personaggio INT NOT NULL,
     PRIMARY KEY(Arco, Personaggio),
     FOREIGN KEY(Personaggio) REFERENCES Personaggi_Giocanti(IDPersonaggio)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Arco) REFERENCES Archi(IDArco)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Partecipazioni(
     Arco INT NOT NULL,
     NumQuest INT NOT NULL,
     Personaggio INT NOT NULL,
     Terminata BIT NOT NULL,
     PRIMARY KEY(Arco, NumQuest, Personaggio),
     FOREIGN KEY(Arco, NumQuest) REFERENCES Quest(Arco, NumQuest)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Personaggio) REFERENCES Personaggi_Giocanti(IDPersonaggio)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Ricompense(
     TipoOggetto INT NOT NULL,
     Arco INT NOT NULL,
     NumQuest INT NOT NULL,
     Quantita INT NOT NULL,
     PRIMARY KEY(TipoOggetto, Arco, NumQuest),
     FOREIGN KEY(TipoOggetto) REFERENCES Tipi_Oggetto(IDTipoOgg),
     FOREIGN KEY(Arco, NumQuest) REFERENCES Quest(Arco, NumQuest)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Raccolti_Necessari(
     OggettoDaRaccogliere INT NOT NULL,
     Arco INT NOT NULL,
     NumQuest INT NOT NULL,
     Quantita INT NOT NULL,
     PRIMARY KEY(OggettoDaRaccogliere, Arco, NumQuest),
     FOREIGN KEY(OggettoDaRaccogliere) REFERENCES Tipi_Oggetto(IDTipoOgg),
     FOREIGN KEY(Arco, NumQuest) REFERENCES Quest(Arco, NumQuest)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

CREATE TABLE Eliminazioni_Necessarie(
     Arco INT NOT NULL,
     NumQuest INT NOT NULL,
     NPCDaEliminare INT NOT NULL,
     PRIMARY KEY(Arco, NumQuest, NPCDaEliminare),
     FOREIGN KEY(NPCDaEliminare) REFERENCES NPC(IDNPC)
          ON UPDATE NO ACTION
          ON DELETE CASCADE,
     FOREIGN KEY(Arco, NumQuest) REFERENCES Quest(Arco, NumQuest)
          ON UPDATE NO ACTION
          ON DELETE CASCADE
);

-- valori di prova per iniziare

INSERT INTO mondi(NomeMondo) VALUES ('Terra di Mezzo'), ('Summer Fields');
INSERT INTO razze(NomeRazza) VALUES ('Umano'), ('Elfo Scuro');
INSERT INTO tipi_aree(NomeTipo, Descrizione, EffettiAggiuntivi) VALUES ("Palude", "Un terreno impervio ricoperto di acquitrini e fanghiglie", "Rallenta di molto i personaggi bassi. Può provocare veleno (1d6 ogni turno: 6 = veleno)."), ("Montagna", "Un terreno ricoperto di neve e ricco di rocce.", "Rallenta di molto i personaggi bassi. Può causare problemi di congelamento.");
INSERT INTO tipi_oggetto(Nome,Peso,Descrizione,CategoriaOggetto) VALUES    ('Pozione di Ripristino Semplice', 0.1, 'Una semplice pozione che ripristina 30HP', 'pozione'),
                                                                           ('Salvia Rossa', 0.01, 'Un ingrediente per creare pozioni', 'ingrediente'),
                                                                           ('Succo di Pomodoro', 0.01, 'Un ingrediente per creare pozioni', 'ingrediente')