INSERT INTO profile(profile_name) VALUES 
('BTP'),
('Client');

INSERT INTO users(profile_id, username, mdp, email) VALUES
(1, 'Inssa', 'inssa', 'inssa@gmail.com'),
(2, 'Jean', 'jean', 'jean@gmail.com');

--Vue pour avoir le montant d'un type de travaux
CREATE OR REPLACE VIEW v_amount_total_work_type AS
SELECT w.id_work_type, w.code, w.designation, COALESCE(sum(w.quantity*w.unit_price), 0)::NUMERIC(10, 2) AS total_amount
FROM work_type w 
GROUP BY w.code, w.designation, w.id_work_type;

--Vue pour joindre le type maison et le type travaux
CREATE OR REPLACE VIEW v_house_and_work_type AS
SELECT h.id_house_type, h.duration, h.designation, h.description, h.surface, w.work_type_id 
FROM house_Type h 
LEFT JOIN work_house_type w
ON h.id_house_type = w.house_type_id;

--Vue pour avoir le montant total d'une construction de maison
CREATE OR REPLACE VIEW v_house_type AS
SELECT h.id_house_type, h.designation, h.duration, h.description, h.surface, 
COALESCE(sum(w.unit_price * w.quantity), 0)::NUMERIC(10, 2) AS total_amount
FROM v_house_and_work_type h
LEFT JOIN work_type w
ON h.work_type_id = w.id_work_type
GROUP BY h.id_house_type, h.duration, h.description, h.designation, h.surface;

--Inserer detail devis
INSERT INTO estimate_detail (estimate_id, work_type_id, code, work_detail_designation, 
unit_id, unit_price, quantity, amount)
SELECT %d, w.work_type_id, d.code, d.designation, d.unit_id, d.unit_price, d.quantity, 
d.unit_price*d.quantity AS amount 
FROM v_house_and_work_type w
JOIN work_type d
ON w.work_type_id = d.id_work_type
WHERE w.id_house_type=%d

--Vue pour le details devis
CREATE OR REPLACE VIEW v_details_estimate AS
SELECT d.estimate_id, d.code, w.designation as work_type, d.work_detail_designation as designation,
u.unit_name as unit, d.unit_price, d.quantity, d.amount
FROM estimate_detail d
LEFT JOIN work_type w
ON w.id_work_type = d.work_type_id
LEFT JOIN unit u
ON u.id_unit = d.unit_id;

--Vue pour les payments d'un devis groupe
CREATE OR REPLACE VIEW v_payment_estimate AS 
SELECT id_estimate, COALESCE(sum(amount), 0)::NUMERIC(10, 2) AS amount
FROM payment 
GROUP BY id_estimate;

--Vue pour le devis en cours avec le paiement effectue
CREATE OR REPLACE VIEW v_estimate_progress AS
SELECT e.id_estimate, e.house_type_designation, e.finish_type_designation, e.start_date, e.duration, 
e.sum_amount_work + ((e.sum_amount_work*e.percent_increase)/100) as amount_total, e.client_phone_number,
COALESCE(p.amount, 0)::NUMERIC(10, 2) AS amount_payed, 
COALESCE((p.amount*100)/(e.sum_amount_work + ((e.sum_amount_work*e.percent_increase)/100)), 0)::NUMERIC(10, 2) AS 
percent_payment, e.ref_estimate, wp.work_progressing, COALESCE(wp.n_work_day, 0) AS n_work_day
FROM estimate e
LEFT JOIN v_payment_estimate p
ON p.id_estimate = e.id_estimate
LEFT JOIN v_work_progress wp
ON wp.id_estimate = e.id_estimate; 

--Vue pour extrayer le mois et l'annee d'un devis avec le montant
CREATE OR REPLACE VIEW v_estimate_year_month AS 
SELECT EXTRACT(MONTH FROM date_estimate) AS MONTH, EXTRACT(YEAR FROM date_estimate) AS year, 
sum_amount_work+((sum_amount_work*percent_increase)/100) AS amount_estimate
FROM estimate;

--Requete pour le graphe histogramme par mois dans une annee
select month, sum(amount_estimate) from v_estimate_year_month
where year=2023
group by month;

--Requete pour le graphe histogramme
select year, sum(amount_estimate) from v_estimate_year_month
group by year;

--Vue pour le type de travaux
CREATE OR REPLACE VIEW v_work_type AS
SELECT w.id_work_type, w.code, w.designation, u.unit_name unit, w.unit_price, w.quantity, w.deleted_at,
(w.quantity*w.unit_price)::NUMERIC(10, 2) AS amount
FROM work_type w
JOIN unit u
ON w.unit_id = u.id_unit;


--------------------Script insertion par select--------------------------------------------
--Insertion dans type maison
INSERT INTO house_type (duration, description, designation, surface)
SELECT duree_travaux, description, type_maison, surface 
FROM maison_travaux_temp GROUP BY duree_travaux, description, type_maison, surface;

--Insertion unite
INSERT INTO unit (unit_name)
SELECT unite FROM maison_travaux_temp GROUP BY unite;

--Insertion dans type travaux
INSERT INTO work_type (code, designation, unit_id, unit_price, quantity)
SELECT mt.code_travaux, mt.type_travaux, u.id_unit, mt.prix_unitaire, mt.quantite
FROM maison_travaux_temp mt
JOIN unit u
on u.unit_name = mt.unite
GROUP BY mt.code_travaux, mt.type_travaux, u.id_unit, mt.prix_unitaire, mt.quantite;

--Insertion dans type travaux maison
INSERT INTO work_house_type (house_type_id, work_type_id)
SELECT h.id_house_type, w.id_work_type
FROM maison_travaux_temp m
JOIN house_type h
ON m.type_maison = h.designation
JOIN work_type w
ON m.code_travaux = w.code;

--Insertion devis
--Insertion finition
INSERT INTO finish_type (finish_type_name, increase_percent)
SELECT finition, taux_finition
FROM devis_temp 
GROUP BY finition, taux_finition;

--Insertion dans devis
INSERT INTO estimate (ref_estimate, client_phone_number, start_date, state, house_type_designation, duration, 
house_description, finish_type_designation, percent_increase, sum_amount_work, id_house_type, 
date_estimate, lieu, surface) 
SELECT ref_devis, d.client, d.date_debut, 1, d.type_maison, h.duration, h.description, finition, taux_finition,
(SELECT COALESCE(SUM(w.quantity*w.unit_price), 0) FROM work_type w WHERE w.id_work_type=wht.work_type_id)::NUMERIC(10, 2),
h.id_house_type, date_devis, lieu, h.surface
FROM devis_temp d
LEFT JOIN house_type h
ON d.type_maison = h.designation
LEFT JOIN work_house_type wht
ON wht.house_type_id = h.id_house_type;

--Insertion dans detail_devis
CREATE OR REPLACE FUNCTION insert_into_detail_Estimate()
RETURNS VOID AS
$$
BEGIN
        
    INSERT INTO estimate_detail (estimate_id, work_type_id, code, work_detail_designation, 
    unit_id, unit_price, quantity, amount)
    SELECT e.id_estimate, w.id_work_type, w.code, w.designation, w.unit_id, w.unit_price, w.quantity, 
    w.unit_price*w.quantity 
    FROM estimate e
    JOIN work_house_type wht
    ON e.id_house_type = wht.house_type_id
    JOIN work_type w
    ON wht.work_type_id = w.id_work_type;
    
END;
$$
LANGUAGE plpgsql;

select insert_into_detail_Estimate();

--Insertion dans payment
INSERT INTO payment (ref_payment, client_phone_number, date_payment, amount, id_estimate)
SELECT p.ref_paiement, e.client_phone_number, p.date_paiement, p.montant, e.id_estimate
FROM paiement_temp p
JOIN estimate e
ON p.ref_devis = e.ref_estimate;

------------------Fonction pour inserer les donnees fichiers csv-------------------------------
--Insertion dans maison et travaux
CREATE OR REPLACE FUNCTION insert_house_work_from_csv()
RETURNS VOID AS
$$
BEGIN
        
    --Insertion dans type maison
    INSERT INTO house_type (duration, description, designation, surface)
    SELECT duree_travaux, description, type_maison, surface 
    FROM maison_travaux_temp GROUP BY duree_travaux, description, type_maison, surface;

    --Insertion unite
    INSERT INTO unit (unit_name)
    SELECT unite FROM maison_travaux_temp GROUP BY unite;

    --Insertion dans type travaux
    INSERT INTO work_type (code, designation, unit_id, unit_price, quantity)
    SELECT mt.code_travaux, mt.type_travaux, u.id_unit, mt.prix_unitaire, mt.quantite
    FROM maison_travaux_temp mt
    JOIN unit u
    on u.unit_name = mt.unite
    GROUP BY mt.code_travaux, mt.type_travaux, u.id_unit, mt.prix_unitaire, mt.quantite;

    --Insertion dans type travaux maison
    INSERT INTO work_house_type (house_type_id, work_type_id)
    SELECT 
    (select h.id_house_type from house_type h where h.designation = m.type_maison), 
    (select w.id_work_type from work_type w where w.code = m.code_travaux and 
    w.designation = m.type_travaux and w.unit_price = m.prix_unitaire and w.quantity = m.quantite)
    FROM maison_travaux_temp m;

END;
$$
LANGUAGE plpgsql;

--Insertion devis
CREATE OR REPLACE FUNCTION insert_estimate_from_csv()
RETURNS VOID AS
$$
BEGIN
        
    --Insertion finition
    INSERT INTO finish_type (finish_type_name, increase_percent)
    SELECT finition, taux_finition
    FROM devis_temp 
    GROUP BY finition, taux_finition;

    --Insertion dans devis
    INSERT INTO estimate (ref_estimate, client_phone_number, start_date, state, house_type_designation, 
    duration, house_description, finish_type_designation, percent_increase, sum_amount_work, 
    id_house_type, date_estimate, lieu, surface) 
    SELECT ref_devis, d.client, d.date_debut, 1, d.type_maison, h.duration, h.description, finition, taux_finition,
    (SELECT COALESCE(sum(w.quantity*w.unit_price), 0)::NUMERIC(10, 2) 
    FROM work_type w JOIN work_house_type wht 
    ON wht.work_type_id=w.id_work_type WHERE h.id_house_type=wht.house_type_id),
    h.id_house_type, date_devis, lieu, h.surface
    FROM devis_temp d
    LEFT JOIN house_type h
    ON d.type_maison = h.designation;

END;
$$
LANGUAGE plpgsql;

--Insertion de paiement
CREATE OR REPLACE FUNCTION insert_payment_from_csv()
RETURNS VOID AS
$$
BEGIN
        
    --Insertion dans payment
    INSERT INTO payment (ref_payment, client_phone_number, date_payment, amount, id_estimate)
    SELECT p.ref_paiement, e.client_phone_number, p.date_paiement, p.montant, e.id_estimate
    FROM paiement_temp p
    JOIN estimate e
    ON p.ref_devis = e.ref_estimate
    ON CONFLICT (ref_payment) DO NOTHING;

END;
$$
LANGUAGE plpgsql;

---------------Script reinitialisation des donnees---------------------------------------------
-- Desactivation des contraintes
CREATE OR REPLACE FUNCTION reinitialisation()
RETURNS VOID AS
$$
BEGIN
        
    SET CONSTRAINTS ALL DEFERRED;

    TRUNCATE paiement_temp, work_progress, maison_travaux_temp, devis_temp, payment, estimate_detail, estimate, 
    work_house_type, unit, work_type, finish_type, house_Type;

    -- Réactivation des contraintes
    SET CONSTRAINTS ALL IMMEDIATE;
    
END;
$$
LANGUAGE plpgsql;

--Script pour ajouter  des contraintes a une colonne
ALTER TABLE payment ADD CONSTRAINT ref_payment_unique_constraint UNIQUE(ref_payment);

--Vue des montant total par mois
CREATE OR REPLACE VIEW v_amount_total_in_month AS
SELECT SUM(amount_estimate)::NUMERIC AS amount_estimate FROM v_estimate_year_month
            GROUP BY month;

----Paiement en avance ou en retard
CREATE TABLE config_paiement_value (
    id_config SERIAL PRIMARY KEY,
    name VARCHAR(50),
    percent DECIMAL
);

--Configuration payment
INSERT INTO configPaiement(name, percent) VALUES 
('reduction', -5),
('normal', 0),
('penal', 5);



CREATE OR REPLACE FUNCTION payment_verification(amount DATE, id_estimate INT) RETURNS TABLE (
	month INT,
	total_amount DECIMAL(12, 2)
) AS $$
BEGIN
	RETURN QUERY
	WITH month_list AS (
		SELECT generate_series(1, 12) AS month 
	)
	SELECT
		ml.month, COALESCE (SUM(d.amount), 0)
	FROM 
		month_list ml 
		LEFT JOIN devis d 
		ON EXTRACT (YEAR FROM d.creation_date) = year 
		AND EXTRACT (MONTH FROM d.creation_date) = ml.month
	GROUP BY ml.month
	ORDER BY ml.month;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION payment_verification(amount DATE, id_estimate INT)
RETURNS VOID AS
$$
BEGIN
        
    
    
END;
$$
LANGUAGE plpgsql;

--Vue avancement travaux
CREATE OR REPLACE VIEW v_work_progress_day AS
SELECT
id_estimate, (SUM(w.date_end - w.date_start)+1)-SUM(n_week_end) AS n_work_day
FROM work_progress w 
GROUP BY id_estimate;

--Vue avancement travaux
CREATE OR REPLACE VIEW v_work_progress AS 
SELECT e.id_estimate, e.ref_estimate, e.start_date, e.duration,
w.n_work_day, 
COALESCE((((w.n_work_day)/e.duration)*100), 0)::NUMERIC(10, 2) AS work_progressing
FROM estimate e
LEFT JOIN v_work_progress_day w
ON e.id_estimate = w.id_estimate; 
