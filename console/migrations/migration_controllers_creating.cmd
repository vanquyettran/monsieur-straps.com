php yii migrate/create add_type_column_role_column_activation_token_column_first_name_column_last_name_column_gender_column_date_of_birth_column_to_user_table --fields="type:smallInteger:notNull:defaultValue(0),role:smallInteger:notNull:defaultValue(0),activation_token:string:unique,first_name:string,last_name:string,gender:smallInteger,date_of_birth:integer"

php yii migrate/create create_game_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),menu_item_label:string,long_description:text,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),sort_order:integer:notNull:defaultValue(0),genre:smallInteger:notNull,created_time:bigInteger:notNull,updated_time:bigInteger:notNull,creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image),screenshot_image_id:integer:foreignKey(image)"

php yii migrate/create create_article_category_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),menu_item_label:string,long_description:text,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),sort_order:integer:notNull:defaultValue(0),type:smallInteger:notNull,created_time:bigInteger:notNull,updated_time:bigInteger:notNull,creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image),parent_id:integer:foreignKey(article_category)"

php yii migrate/create create_article_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),menu_item_label:string,description:string(511):notNull,content:text:notNull,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),view_count:integer:notNull:defaultValue(0),published_time:bigInteger:notNull,created_time:bigInteger:notNull,updated_time:bigInteger:notNull,creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image),article_category_id:integer:notNull:foreignKey(article_category),game_id:integer:foreignKey(game)"

php yii migrate/create create_site_param_table --fields="name:string:notNull,value:string(2047):notNull,sort_order:integer:notNull:defaultValue(0)"

php yii migrate/create create_banner_table --fields="title:string:notNull,link:string(511),position:smallInteger:notNull,sort_order:integer:notNull:defaultValue(0),start_time:bigInteger:notNull:defaultValue(0),end_time:bigInteger:notNull:defaultValue(0),active:smallInteger:notNull:defaultValue(0),image_id:integer:notNull:foreignKey(image)"

php yii migrate/create create_static_page_info_table --fields="name:string:notNull,url_pattern:string:notNull:unique,route:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),menu_item_label:string,long_description:text,active:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),avatar_image_id:integer:foreignKey(image)"

php yii migrate/create create_junction_table_for_game_and_image_tables --fields="sort_order:smallInteger"

php yii migrate/create create_menu_item_table --fields="menu_id:integer:notNull,label:string:notNull,sort_order:integer:notNull:defaultValue(0),anchor_target:string,anchor_rel:string,link:string(511),article_category_id:integer:foreignKey,article_id:integer:foreignKey"

php yii migrate/create create_article_tag_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),long_description:text,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),sort_order:integer:notNull:defaultValue(0),created_time:dateTime:notNull,updated_time:dateTime:notNull,creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image)"

php yii migrate/create create_junction_table_for_article_and_article_tag_tables

yii migrate/create add_display_areas_column_to_article_category_table --fields="display_areas:string"

php yii migrate/create create_junction_table_for_article_and_article_tables





yii migrate/create create_product_category_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),long_description:text,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),sort_order:integer:notNull:defaultValue(0),display_areas:string,type:smallInteger:notNull,created_time:dateTime:notNull,updated_time:dateTime:notNull,creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image),parent_id:integer:foreignKey(product_category)"

yii migrate/create create_product_table --fields="name:string:notNull,slug:string:notNull:unique,heading:string,page_title:string,meta_title:string,meta_keywords:string(511),meta_description:string(511),description:string(511),code:string:notNull:unique,price:integer:notNull,long_description:text,details:text,active:smallInteger:notNull:defaultValue(0),visible:smallInteger:notNull:defaultValue(0),featured:smallInteger:notNull:defaultValue(0),allow_indexing:smallInteger:notNull:defaultValue(0),allow_following:smallInteger:notNull:defaultValue(0),production_status:smallInteger:notNull,sort_order:integer:notNull:defaultValue(0),published_time:dateTime:notNull,created_time:dateTime:notNull,updated_time:dateTime:notNull,view_count:integer:notNull:defaultValue(0),creator_id:integer:notNull:foreignKey(user),updater_id:integer:notNull:foreignKey(user),avatar_image_id:integer:foreignKey(image),product_category_id:integer:foreignKey"

yii migrate/create create_product_attribute_group_table --fields="name:string:notNull:unique,type:smallInteger:notNull,sort_order:integer:notNull:defaultValue(0)"

yii migrate/create create_product_attribute_table --fields="name:string:notNull,value:string:notNull,illustration_image_id:integer:foreignKey(image),sort_order:integer:notNull:defaultValue(0),product_attribute_group_id:integer:notNull:foreignKey"

yii migrate/create create_junction_table_for_product_and_product_attribute_tables

yii migrate/create create_product_customization_table --fields="name:string:notNull:unique,details:text,price:integer:notNull,sort_order:integer:notNull:defaultValue(0),featured:smallInteger(1):notNull:defaultValue(0),available:smallInteger(1):notNull:defaultValue(0),product_id:integer:notNull:foreignKey"

yii migrate/create create_junction_table_for_product_customization_and_image_tables --fields="sort_order:integer:notNull:defaultValue(0)"

yii migrate/create create_junction_table_for_product_and_image_tables --fields="sort_order:integer:notNull:defaultValue(0)"

yii migrate/create create_junction_table_for_product_customization_and_product_attribute_tables

yii migrate/create create_junction_table_for_product_and_product_tables

yii migrate/create create_product_discount_table --fields="title:string:notNull,occasion:text,percentage:smallInteger(2):notNull,start_time:dateTime:notNull,end_time:dateTime:notNull"

yii migrate/create create_junction_table_for_product_and_product_discount_tables

yii migrate/create create_junction_table_for_product_category_and_product_discount_tables

yii migrate/create create_junction_table_for_product_customization_and_product_discount_tables --fields="product_id:integer:notNull:foreignKey"

yii migrate/create create_junction_table_for_product_and_tag_tables

yii migrate/create create_junction_table_for_product_category_and_image_tables

yii migrate/create add_product_id_column_product_category_id_column_to_menu_item_table --fields="product_id:integer:foreignKey,product_category_id:integer:foreignKey"



yii migrate/create create_junction_table_for_product_category_and_product_attribute_group_tables