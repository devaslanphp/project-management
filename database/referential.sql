INSERT INTO `ticket_priorities` (`id`, `name`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Low', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(2, 'Normal', '#CECECE', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(3, 'High', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56');

INSERT INTO `ticket_statuses` (`id`, `name`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`, `order`, `project_id`) VALUES
(1, 'Backlog', '#cecece', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 1, NULL),
(2, 'In progress', '#ff7f00', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 2, NULL),
(3, 'Done', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 3, NULL),
(4, 'Archived', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 4, NULL);

INSERT INTO `ticket_types` (`id`, `name`, `icon`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Task', 'heroicon-o-check-circle', '#00FFFF', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(2, 'Evolution', 'heroicon-o-clipboard-list', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(3, 'Bug', 'heroicon-o-x', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56');


INSERT INTO `activities` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Programming', 'Programming related activities', '2023-01-09 13:10:52', '2023-01-09 13:10:52', NULL),
(2, 'Testing', 'Testing related activities', '2023-01-09 13:10:52', '2023-01-09 13:10:52', NULL),
(3, 'Learning', 'Activities related to learning and training', '2023-01-09 13:10:52', '2023-01-09 13:10:52', NULL),
(4, 'Research', 'Activities related to research', '2023-01-09 13:10:52', '2023-01-09 13:10:52', NULL),
(5, 'Other', 'Other activities', '2023-01-09 13:10:52', '2023-01-09 13:10:52', NULL);

ALTER TABLE `ticket_priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `ticket_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `ticket_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `activities`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
