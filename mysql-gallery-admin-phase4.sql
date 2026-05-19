-- One Source Air & Energy Ltd
-- MySQL gallery admin phase 4

ALTER TABLE projects
    ADD COLUMN IF NOT EXISTS show_in_gallery TINYINT(1) NOT NULL DEFAULT 1;

UPDATE projects
SET show_in_gallery = featured
WHERE show_in_gallery IS NULL;
