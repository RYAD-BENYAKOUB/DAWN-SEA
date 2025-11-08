ALTER TABLE recommandation
ADD COLUMN Image VARCHAR(255) NULL;

UPDATE recommandation
SET Image = CONCAT('PFE2/IMG/BDD/', 
                   LOWER(REPLACE(REPLACE(Titre, '’', ''), ' ', '_')), 
                   '.jpg')
WHERE Titre IS NOT NULL;
