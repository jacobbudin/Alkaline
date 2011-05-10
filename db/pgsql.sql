CREATE TABLE "cities" ("city_id" INTEGER PRIMARY KEY, "city_name" TEXT, "city_state" TEXT, "country_code" TEXT, "city_name_raw" TEXT, "city_name_alt" TEXT, "city_pop" INTEGER, "city_lat" REAL, "city_long" REAL, "city_class" TEXT, "city_code" TEXT);
CREATE TABLE "comments" ("comment_id" SERIAL PRIMARY KEY, "comment_response" INTEGER, "image_id" INTEGER, "post_id" INTEGER, "user_id" INTEGER, "comment_created" TIMESTAMP, "comment_status" INTEGER, "comment_text" TEXT, "comment_text_raw" TEXT, "comment_markup" TEXT, "comment_author_name" TEXT, "comment_author_uri" TEXT, "comment_author_email" TEXT, "comment_author_ip" TEXT, "comment_author_avatar" TEXT);
CREATE TABLE "countries" ("country_id" SMALLINT PRIMARY KEY, "country_code" TEXT, "country_name" TEXT);
CREATE TABLE "exifs" ("exif_id" SERIAL PRIMARY KEY, "image_id" INTEGER, "exif_key" TEXT, "exif_name" TEXT, "exif_value" TEXT);
CREATE TABLE "extensions" ("extension_id" SERIAL PRIMARY KEY, "extension_uid" TEXT, "extension_class" TEXT, "extension_title" TEXT, "extension_status" INTEGER, "extension_build" INTEGER, "extension_build_latest" INTEGER, "extension_version" TEXT, "extension_version_latest" TEXT, "extension_hooks" TEXT, "extension_preferences" TEXT, "extension_folder" TEXT, "extension_file" TEXT, "extension_description" TEXT, "extension_creator_name" TEXT, "extension_creator_uri" TEXT);
CREATE TABLE "guests" ("guest_id" SERIAL PRIMARY KEY, "guest_title" TEXT, "guest_key" TEXT, "guest_sets" TEXT, "guest_last_login" TIMESTAMP, "guest_created" TIMESTAMP, "guest_views" INTEGER);
CREATE TABLE "links" ("link_id" SERIAL PRIMARY KEY, "image_id" INTEGER, "tag_id" INTEGER);
CREATE TABLE "images" ("image_id" SERIAL PRIMARY KEY, "user_id" INTEGER, "right_id" INTEGER, "image_ext" TEXT, "image_mime" TEXT, "image_title" TEXT, "image_description" TEXT, "image_description_raw" TEXT, "image_markup" TEXT, "image_privacy" INTEGER, "image_name" TEXT, "image_colors" TEXT, "image_color_r" INTEGER, "image_color_g" INTEGER, "image_color_b" INTEGER, "image_color_h" INTEGER, "image_color_s" INTEGER, "image_color_l" INTEGER, "image_taken" TIMESTAMP, "image_uploaded" TIMESTAMP, "image_published" TIMESTAMP, "image_modified" TIMESTAMP, "image_geo" TEXT, "image_geo_lat" REAL, "image_geo_long" REAL, "image_views" INTEGER, "image_comment_disabled" INTEGER, "image_comment_count" INTEGER, "image_height" INTEGER, "image_width" INTEGER);
CREATE TABLE "pages" ("page_id" SERIAL PRIMARY KEY, "user_id" INTEGER, "page_title" TEXT, "page_title_url" TEXT, "page_text" TEXT, "page_text_raw" TEXT, "page_markup" TEXT, "page_images" TEXT, "page_views" INTEGER, "page_words" INTEGER, "page_created" TIMESTAMP, "page_modified" TIMESTAMP);
CREATE TABLE "posts" ("post_id" SERIAL PRIMARY KEY, "post_title" TEXT, "post_title_url" TEXT, "post_text" TEXT, "post_text_raw" TEXT, "post_markup" TEXT, "post_images" TEXT, "post_views" INTEGER, "post_words" INTEGER, "post_created" TIMESTAMP, "post_published" TIMESTAMP, "post_modified" TIMESTAMP, "post_comment_count" INTEGER, "post_comment_disabled" INTEGER);
CREATE TABLE "rights" ("right_id" SERIAL PRIMARY KEY, "right_title" TEXT, "right_uri" TEXT, "right_image" TEXT, "right_description" TEXT, "right_description_raw" TEXT, "right_markup" TEXT, "right_created" TIMESTAMP, "right_modified" TIMESTAMP, "right_image_count" INTEGER);
CREATE TABLE "sets" ("set_id" SERIAL PRIMARY KEY, "set_title" TEXT, "set_title_url" TEXT, "set_type" TEXT, "set_description" TEXT, "set_description_raw" TEXT, "set_markup" TEXT, "set_images" TEXT, "set_views" INTEGER, "set_image_count" INTEGER, "set_call" TEXT, "set_request" TEXT, "set_modified" TIMESTAMP, "set_created" TIMESTAMP);
CREATE TABLE "sizes" ("size_id" SERIAL PRIMARY KEY, "size_title" TEXT, "size_label" TEXT, "size_height" INTEGER, "size_width" INTEGER, "size_type" TEXT, "size_append" TEXT, "size_prepend" TEXT, "size_watermark" INTEGER);
CREATE TABLE "stats" ("stat_id" SERIAL PRIMARY KEY, "stat_session" TEXT, "stat_date" TIMESTAMP, "stat_duration" INTEGER, "stat_referrer" TEXT, "stat_page" TEXT, "stat_page_type" TEXT, "stat_local" INTEGER, "user_id" INTEGER, "guest_id" INTEGER);
CREATE TABLE "tags" ("tag_id" SERIAL PRIMARY KEY, "tag_name" TEXT, "tag_parents" TEXT);
CREATE TABLE "themes" ("theme_id" SERIAL PRIMARY KEY, "theme_uid" TEXT, "theme_title" TEXT, "theme_build" INTEGER, "theme_build_latest" INTEGER, "theme_version" TEXT, "theme_version_latest" TEXT, "theme_folder" TEXT, "theme_creator_name" TEXT, "theme_creator_uri" TEXT);
CREATE TABLE "users" ("user_id" SERIAL PRIMARY KEY, "user_user" TEXT, "user_pass" TEXT, "user_pass_salt" TEXT, "user_key" TEXT, "user_name" TEXT, "user_email" TEXT, "user_last_login" TIMESTAMP, "user_created" TIMESTAMP, "user_permissions" TEXT, "user_preferences" TEXT, "user_image_count" INTEGER);