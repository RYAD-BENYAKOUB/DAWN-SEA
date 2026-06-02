-- Schema SQL pour Supabase (PostgreSQL)
-- Ce fichier contient les définitions des tables traduits de MySQL vers PostgreSQL.
-- Important : les noms de tables et colonnes utilisent des guillemets doubles (ex: "utilisateur") pour conserver la casse d'origine.

-- 1. Table des utilisateurs
CREATE TABLE IF NOT EXISTS "utilisateur" (
    "ID_Utilisateur" SERIAL PRIMARY KEY,
    "Nom" VARCHAR(30) DEFAULT NULL,
    "Prenom" VARCHAR(30) DEFAULT NULL,
    "Email" VARCHAR(50) UNIQUE DEFAULT NULL,
    "Mot_de_passe" VARCHAR(255) DEFAULT NULL,
    "Num_de_telephone" VARCHAR(50) DEFAULT NULL,
    "Pays_de_naissance" VARCHAR(50) DEFAULT NULL,
    "Date_de_naissance" DATE DEFAULT NULL,
    "Role" VARCHAR(20) DEFAULT 'user'
);

-- Note de migration pour bases existantes :
-- ALTER TABLE "utilisateur" ADD COLUMN IF NOT EXISTS "Role" VARCHAR(20) DEFAULT 'user';
-- UPDATE "utilisateur" SET "Role" = 'superadmin' WHERE "Email" = 'ryadbenyakoub@gmail.com';


-- 2. Table des lieux
CREATE TABLE IF NOT EXISTS "lieu" (
    "ID_Lieu" SERIAL PRIMARY KEY,
    "Nom" VARCHAR(50) DEFAULT NULL,
    "Description" TEXT DEFAULT NULL,
    "Address" VARCHAR(50) DEFAULT NULL,
    "Image" BYTEA DEFAULT NULL
);

-- 3. Table des tags
CREATE TABLE IF NOT EXISTS "tag" (
    "ID_Tag" SERIAL PRIMARY KEY,
    "Nom" VARCHAR(50) DEFAULT NULL
);

-- 4. Table des recommandations
CREATE TABLE IF NOT EXISTS "recommandation" (
    "ID_Recommandation" SERIAL PRIMARY KEY,
    "ID_Lieu" INT REFERENCES "lieu"("ID_Lieu") ON DELETE CASCADE,
    "Titre" VARCHAR(55) DEFAULT NULL,
    "Description" TEXT DEFAULT NULL,
    "Note_Generale" INT DEFAULT NULL,
    "Image" VARCHAR(55) DEFAULT NULL
);

-- 5. Table de jointure recommandation <-> tag
CREATE TABLE IF NOT EXISTS "recommandation_tag" (
    "ID_Recommandation" INT REFERENCES "recommandation"("ID_Recommandation") ON DELETE CASCADE,
    "ID_Tag" INT REFERENCES "tag"("ID_Tag") ON DELETE CASCADE,
    PRIMARY KEY ("ID_Recommandation", "ID_Tag")
);
