/*



*/

CREATE TABLE `cities` (
  `city_id` INTEGER,
  `city_name` TEXT,
  `city_state` TEXT,
  `country_code` TEXT,
  `city_name_raw` TEXT,
  `city_name_alt` TEXT,
  `city_pop` INTEGER,
  `city_lat` REAL,
  `city_long` REAL,
  `city_class` TEXT,
  `city_code` TEXT,
  PRIMARY KEY  (`city_id`)
);



CREATE TABLE `comments` (
  `comment_id` INTEGER,
  `photo_id` INTEGER,
  `user_id` INTEGER,
  `comment_created` TEXT,
  `comment_status` INTEGER,
  `comment_text` TEXT,
  `comment_text_raw` TEXT,
  `comment_author_name` TEXT,
  `comment_author_url` TEXT,
  `comment_author_email` TEXT,
  `comment_author_ip` TEXT,
  `comment_author_avatar` TEXT,
  PRIMARY KEY  (`comment_id`)
);



CREATE TABLE `countries` (
  `country_id` INTEGER,
  `country_code` TEXT,
  `country_name` TEXT,
  PRIMARY KEY  (`country_id`)
);



CREATE TABLE `exifs` (
  `exif_id` INTEGER,
  `photo_id` INTEGER,
  `exif_key` TEXT,
  `exif_name` TEXT,
  `exif_value` TEXT,
  PRIMARY KEY  (`exif_id`)
);



CREATE TABLE `extensions` (
  `extension_id` INTEGER,
  `extension_uid` TEXT,
  `extension_class` TEXT,
  `extension_title` TEXT,
  `extension_status` INTEGER,
  `extension_build` INTEGER,
  `extension_version` TEXT,
  `extension_hooks` TEXT,
  `extension_preferences` TEXT,
  `extension_folder` TEXT,
  `extension_file` TEXT,
  `extension_description` TEXT,
  `extension_creator` TEXT,
  `extension_creator_url` TEXT,
  PRIMARY KEY  (`extension_id`)
);



CREATE TABLE `guests` (
  `guest_id` INTEGER,
  `guest_title` TEXT,
  `guest_key` TEXT,
  `guest_last_login` TEXT,
  `guest_created` TEXT,
  `guest_views` INTEGER,
  PRIMARY KEY  (`guest_id`)
);



CREATE TABLE `links` (
  `link_id` INTEGER,
  `photo_id` INTEGER,
  `tag_id` INTEGER,
  PRIMARY KEY  (`link_id`)
);



CREATE TABLE `pages` (
  `page_id` INTEGER,
  `page_title` TEXT,
  `page_title_url` TEXT,
  `page_text` TEXT,
  `page_text_raw` TEXT,
  `page_markup` TEXT,
  `page_views` INTEGER,
  `page_words` INTEGER,
  `page_created` TEXT,
  `page_modified` TEXT,
  PRIMARY KEY  (`page_id`)
);



CREATE TABLE `photos` (
  `photo_id` INTEGER,
  `user_id` INTEGER,
  `right_id` INTEGER,
  `photo_ext` TEXT,
  `photo_mime` TEXT,
  `photo_title` TEXT,
  `photo_description` TEXT,
  `photo_description_raw` TEXT,
  `photo_privacy` INTEGER,
  `photo_name` TEXT,
  `photo_colors` TEXT,
  `photo_color_r` INTEGER,
  `photo_color_g` INTEGER,
  `photo_color_b` INTEGER,
  `photo_color_h` INTEGER,
  `photo_color_s` INTEGER,
  `photo_color_l` INTEGER,
  `photo_taken` TEXT,
  `photo_uploaded` TEXT,
  `photo_published` TEXT,
  `photo_updated` TEXT,
  `photo_geo` TEXT,
  `photo_geo_lat` REAL,
  `photo_geo_long` REAL,
  `photo_views` INTEGER,
  `photo_comment_count` INTEGER,
  `photo_height` INTEGER,
  `photo_width` INTEGER,
  PRIMARY KEY  (`photo_id`)
);



CREATE TABLE `piles` (
  `pile_id` INTEGER,
  `pile_title` TEXT,
  `pile_title_url` TEXT,
  `pile_type` TEXT,
  `pile_description` TEXT,
  `pile_photos` TEXT,
  `pile_views` INTEGER,
  `pile_photo_count` INTEGER,
  `pile_call` TEXT,
  `pile_modified` TEXT,
  `pile_created` TEXT,
  PRIMARY KEY  (`pile_id`)
);



CREATE TABLE `rights` (
  `right_id` INTEGER,
  `right_title` TEXT,
  `right_default` INTEGER,
  `right_url` TEXT,
  `right_image` TEXT,
  `right_description` TEXT,
  `right_modified` TEXT,
  `right_photo_count` INTEGER,
  PRIMARY KEY  (`right_id`)
);



CREATE TABLE `sizes` (
  `size_id` INTEGER,
  `size_title` TEXT,
  `size_height` INTEGER,
  `size_width` INTEGER,
  `size_type` TEXT,
  `size_append` TEXT,
  `size_prepend` TEXT,
  PRIMARY KEY  (`size_id`)
);



CREATE TABLE `stats` (
  `stat_id` INTEGER,
  `stat_session` TEXT,
  `stat_date` TEXT,
  `stat_duration` INTEGER,
  `stat_referrer` TEXT,
  `stat_page` TEXT,
  `stat_page_type` TEXT,
  `stat_local` INTEGER,
  `user_id` INTEGER,
  `guest_id` INTEGER,
  PRIMARY KEY  (`stat_id`)
);



CREATE TABLE `tags` (
  `tag_id` INTEGER,
  `tag_name` TEXT,
  PRIMARY KEY  (`tag_id`)
);



CREATE TABLE `themes` (
  `theme_id` INTEGER,
  `theme_uid` TEXT,
  `theme_title` TEXT,
  `theme_default` INTEGER,
  `theme_build` INTEGER,
  `theme_version` TEXT,
  `theme_folder` TEXT,
  `theme_creator` TEXT,
  `theme_creator_url` TEXT,
  PRIMARY KEY  (`theme_id`)
);



CREATE TABLE `users` (
  `user_id` INTEGER,
  `user_user` TEXT,
  `user_pass` TEXT,
  `user_key` TEXT,
  `user_name` TEXT,
  `user_email` TEXT,
  `user_last_login` TEXT,
  `user_created` TEXT,
  `user_permissions` TEXT,
  `user_preferences` TEXT,
  `user_photo_count` INTEGER,
  PRIMARY KEY  (`user_id`)
);

