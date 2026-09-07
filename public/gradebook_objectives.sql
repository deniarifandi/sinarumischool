-- ============================================================
-- Gradebook Objective-Based (Tab 2)
--
-- REVISI: kolom objektif AUTO-generate dari objectives
-- (objective.term_id = term gradebook, subject via outcome).
-- Tidak ada tabel join kolom lagi; score langsung key
-- (gradebook_id, objective_id, student_id).
--
-- Host: localhost  /  DB: sinarumischool2
-- ============================================================

CREATE TABLE IF NOT EXISTS `gradebook_objective_scores` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gradebook_id` int(10) UNSIGNED NOT NULL,
  `objective_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_gb_obj_student` (`gradebook_id`,`objective_id`,`student_id`),
  KEY `idx_gos_objective` (`objective_id`),
  KEY `idx_gos_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;