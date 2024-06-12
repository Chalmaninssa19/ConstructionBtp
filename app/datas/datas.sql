create database tsakitsaky;

.\c tsakitsaky;

create sequence seq_utilisateur START WITH 1 INCREMENT BY 1;
create sequence seq_profil START WITH 1 INCREMENT BY 1;

create table profil (
    id_profil INTEGER DEFAULT nextval('seq_profil'::regclass) NOT NULL PRIMARY KEY,
    libelle varchar(50)
);

create table utilisateur (
    id_utilisateur INTEGER DEFAULT nextval('seq_utilisateur'::regclass) NOT NULL PRIMARY KEY,
    date_naissance date,
    sexe integer,
    username varchar(100),
    mdp varchar(100),
    email varchar(100),
    id_profil integer references profil(id_profil)
);

INSERT INTO profil (libelle) VALUES ('Admin');
INSERT INTO profil (libelle) VALUES ('Normal');

INSERT INTO utilisateur (date_naissance, sexe, username, mdp, email, id_profil) VALUES 
('2002-06-19', 1, 'chalman', 'chalman', 'chalman@gmail.com', 1),
('2005-08-28', 1, 'zaid', 'zaid', 'zaid@gmail.com', 2); 