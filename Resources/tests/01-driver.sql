-- --------------------------------------------------------
-- This file creates the configuration needed for the tests
-- --------------------------------------------------------

SELECT @maxId := IF(ISNULL(MAX(`id`)), 0, MAX(`id`)) FROM `index_engine_driver_configuration`;

INSERT INTO `index_engine_driver_configuration` VALUES
  (@maxId+1, "elasticsearch", "Local elasticsearch", "eyJzZXJ2ZXJzIjpbImxvY2FsaG9zdDo5MjAwIl0sIm51bWJlcl9vZl9zaGFyZHMiOjEsIm51bWJlcl9vZl9yZXBsaWNhcyI6MCwic2F2ZV9zb3VyY2UiOnRydWV9")
;

INSERT IGNORE INTO `index_engine_index` VALUES
  (NULL, 1, "unique_code", "foobar", "sql query", "address", "W10=", "eyJjcml0ZXJpYSI6W10sInF1ZXJ5IjoiU0VMRUNUICogRlJPTSBwcm9kdWN0IGxpbWl0IDEwMDsiLCJtYXBwaW5nIjp7ImlkIjoiaW50ZWdlciIsInJlZiI6InN0cmluZyIsInZpc2libGUiOiJib29sZWFuIn19", @maxId+1, NOW(), NOW(), 1, NOW(), "IndexEngine")
;