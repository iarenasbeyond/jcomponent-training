DROP TABLE IF EXISTS `#__ponente_grupo`;
DROP TABLE IF EXISTS `#__ponente_album`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_ponente.%');