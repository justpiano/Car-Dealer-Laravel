<?php
    /**
     * Items Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: items.class.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Items
    {
        
        const lTable = "listings";
        const liTable = "listings_info";
        const acTable = "activity";
        const gTable = "gallery";
        const cxTable = "compare";
        const xTable = "cart";
        
        const MAXIMG = 5242880;
        const THUMBW = 400;
        const THUMBH = 300;
        
        const MAXFILE = 52428800;
        const FILES = "pdf";
        
        
        /**
         * Items::__construct()
         *
         */
        public function __construct()
        {
        }
        
        
        /**
         * Items::Index()
         *
         * @return void
         */
        public function Index()
        {
            
            $enddate = (Validator::post('enddate_submit') && $_POST['enddate_submit'] <> "") ? Validator::sanitize(Db::toDate($_POST['enddate_submit'], false)) : date("Y-m-d");
            $fromdate = Validator::post('fromdate_submit') ? Validator::sanitize(Db::toDate($_POST['fromdate_submit'], false)) : null;
            
            if (Validator::post('fromdate') && $_POST['fromdate'] <> "") {
                $counter = Db::Go()->count(self::lTable, "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND status = 1")->run();
                $where = "WHERE l.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND l.status = 1";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $where = "WHERE `nice_title` REGEXP '^" . $letter . "' AND status = 1";
                $counter = Db::Go()->count(self::lTable, "WHERE `nice_title` REGEXP '^" . $letter . "' AND status = 1 LIMIT 1")->run();
                
            } else {
                $counter = Db::Go()->count(self::lTable)->where("status", 1, "=")->run();
                $where = "WHERE l.status = 1";
            }
            
            if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
                list($sort, $order) = explode("|", $_GET['order']);
                $sort = Validator::sanitize($sort, "default", 10);
                $order = Validator::sanitize($order, "default", 4);
                if (in_array($sort, array(
                    "year",
                    "make_id",
                    "model_id",
                    "category",
                    "price"))) {
                    $ord = ($order == 'DESC') ? " DESC" : " ASC";
                    $sorting = $sort . $ord;
                } else {
                    $sorting = " l.created DESC";
                }
            } else {
                $sorting = " l.created DESC";
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                l.*,
                cd.name AS cdname,
                ct.name AS ctname,
                u.username
              FROM
                `" . self::lTable . "` AS l
                LEFT JOIN `" . Content::cdTable . "` AS cd
                  ON cd.id = l.vcondition
                LEFT JOIN `" . Content::ctTable . "` AS ct
                  ON ct.id = l.category
                LEFT JOIN `" . Users::mTable . "` AS u
                  ON u.id = l.user_id
              $where
              ORDER BY $sorting" . $pager->limit;
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->LST_TITLE];
            $tpl->title = Lang::$word->LST_TITLE;
            $tpl->data = Db::Go()->rawQuery($sql)->run();
            $tpl->pager = $pager;
            $tpl->template = 'admin/items.tpl.php';
        }
        
        /**
         * Items::Save()
         *
         * @return void
         */
        public function Save()
        {
            App::Session()->set("imgtoken", Utility::randNumbers(4));
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LST_TITLE2;
            
            $tpl->features = Db::Go()->select(Content::fTable)->orderBy("sorting", "ASC")->run();
            $tpl->locations = Db::Go()->select(Content::lcTable, array("id", "name"))->run();
            $tpl->makes = Db::Go()->select(Content::mkTable, array("id", "name"))->run();
            $tpl->categories = Db::Go()->select(Content::ctTable, array("id", "name"))->run();
            $tpl->conditions = Db::Go()->select(Content::cdTable, array("id", "name"))->run();
            $tpl->transmissions = Db::Go()->select(Content::trTable, array("id", "name"))->run();
            $tpl->fuel = Db::Go()->select(Content::fuTable, array("id", "name"))->run();
            $tpl->colors = Content::colorList();
            $tpl->template = 'admin/items.tpl.php';
        }
        
        /**
         * Items::Edit()
         *
         * @param int $id
         * @return void
         */
        public function Edit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LST_TITLE1;
            $tpl->crumbs = ['admin', 'items', 'edit'];
            
            if (!$row = Db::Go()->select(self::lTable)->where("id", $id, "=")->where("status", 1, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [items.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->features = Db::Go()->select(Content::fTable)->orderBy("sorting", "ASC")->run();
                $tpl->fin = Utility::jSonToArray($tpl->row->features);
                $tpl->gallery = Db::Go()->select(self::gTable)->where("listing_id", $tpl->row->id, "=")->orderBy("sorting", "ASC")->run();
                $tpl->locations = Db::Go()->select(Content::lcTable, array("id", "name"))->run();
                $tpl->makes = Db::Go()->select(Content::mkTable, array("id", "name"))->run();
                $tpl->models = Db::Go()->select(Content::mdTable, array("id", "name"))->where("make_id", $tpl->row->make_id, "=")->run();
                $tpl->categories = Db::Go()->select(Content::ctTable, array("id", "name"))->run();
                $tpl->conditions = Db::Go()->select(Content::cdTable, array("id", "name"))->run();
                $tpl->transmissions = Db::Go()->select(Content::trTable, array("id", "name"))->run();
                $tpl->fuel = Db::Go()->select(Content::fuTable, array("id", "name"))->run();
                $tpl->colors = Content::colorList();
                $tpl->template = 'admin/items.tpl.php';
            }
        }
        
        /**
         * Items::processItem()
         *
         * @return void
         */
        public function processItem()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("make_id", Lang::$word->LST_MAKE)->required()->numeric()
                ->set("model_id", Lang::$word->LST_MODEL)->required()->numeric()
                ->set("year", Lang::$word->LST_YEAR)->required()->numeric()->exact_len(4)
                ->set("location", Lang::$word->LST_ROOM)->required()->numeric()
                ->set("category", Lang::$word->LST_CAT)->required()->numeric()
                ->set("vcondition", Lang::$word->LST_COND)->required()->numeric()
                ->set("transmission", Lang::$word->LST_TRANS)->required()->numeric()
                ->set("mileage", Lang::$word->LST_ODM)->required()->numeric()
                ->set("fuel", Lang::$word->LST_FUEL)->required()->numeric()
                ->set("doors", Lang::$word->LST_DOORS)->required()->numeric()
                ->set("expire_submit", Lang::$word->EXPIRE)->required()->date()
                ->set("price", Lang::$word->LST_PRICE)->required()->float();
            
            $validate
                ->set("price_sale", Lang::$word->LST_DPRICE_S)->float()
                ->set("mileage", Lang::$word->LST_ODM)->numeric()
                ->set("torque", Lang::$word->LST_TORQUE)->string()
                ->set("color_e", Lang::$word->LST_INTC)->string()
                ->set("color_i", Lang::$word->LST_EXTC)->string()
                ->set("drive_train", Lang::$word->LST_COND)->string()
                ->set("engine", Lang::$word->LST_ENGINE)->string()
                ->set("top_speed", Lang::$word->LST_SPEED)->numeric()
                ->set("horse_power", Lang::$word->LST_POWER)->string()
                ->set("towing_capacity", Lang::$word->LST_TOWING)->string()
                ->set("vin", Lang::$word->LST_VIN)->string()
                ->set("stock_id", Lang::$word->LST_STOCK)->string()
                ->set("is_owner", Lang::$word->LST_PRICE)->numeric()
                ->set("slug", Lang::$word->LST_SLUG)->string()
                ->set("title", Lang::$word->LST_NAME)->string()
                ->set("notes", Lang::$word->LST_NOTES)->string()
                ->set("body", Lang::$word->LST_DESC)->text("advanced")
                ->set("metakey", Lang::$word->LST_METAKEY)->string()
                ->set("metadesc", Lang::$word->LST_METADESC)->string()
                ->set("features", Lang::$word->LST_FEATURES)->one();
            
            (Filter::$id) ? $this->_updateItem($validate) : $this->_addItem($validate);
        }
        
        /**
         * Items::_updateItem()
         *
         * @param $validate
         * @return void
         */
        public function _updateItem($validate)
        {
            
            $thumb = File::upload("thumb", self::MAXIMG, "png,jpg,jpeg,webp");
            $file = File::upload("file", self::MAXFILE, self::FILES);
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $mid = self::doTitle($safe->model_id);
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'slug' => (empty($safe->slug)) ? $safe->year . '-' . $mid : Url::doSeo($safe->slug),
                    'nice_title' => ucwords(str_replace("-", " ", $mid)),
                    'location' => $safe->location,
                    'stock_id' => $safe->stock_id,
                    'vin' => $safe->vin,
                    'make_id' => $safe->make_id,
                    'model_id' => $safe->model_id,
                    'year' => $safe->year,
                    'vcondition' => $safe->vcondition,
                    'category' => $safe->category,
                    'mileage' => $safe->mileage,
                    'torque' => $safe->torque,
                    'price' => $safe->price,
                    'price_sale' => $safe->price_sale,
                    'color_e' => $safe->color_e,
                    'color_i' => $safe->color_i,
                    'doors' => $safe->doors,
                    'fuel' => $safe->fuel,
                    'drive_train' => $safe->drive_train,
                    'engine' => $safe->engine,
                    'transmission' => $safe->transmission,
                    'top_speed' => $safe->top_speed,
                    'horse_power' => $safe->horse_power,
                    'features' => json_encode($safe->features),
                    'towing_capacity' => $safe->towing_capacity,
                    'body' => $safe->body ? Url::in_url($safe->body) : "",
                    'metakey' => $safe->metakey,
                    'metadesc' => $safe->metadesc,
                    'notes' => $safe->notes,
                    'expire' => $safe->expire_submit . ' 23:59:59',
                    'modified' => Db::toDate(),
                    'gallery' => Db::Go()->select(self::gTable)->where("listing_id", Filter::$id, "=")->orderBy("sorting", "ASC")->run('json'),
                    'status' => isset($_POST['status']) ? 1 : 0,
                    'sold' => isset($_POST['sold']) ? 1 : 0,
                    'is_owner' => $safe->is_owner,
                    'featured' => isset($_POST['featured']) ? 1 : 0
                );
                $datax['title'] = (empty($safe->title)) ? str_replace("-", " ", $data['slug']) : $safe->title;
                
                if (isset($_POST['sold'])) {
                    $datax['soldexpire'] = Db::toDate();
                }
                
                if (empty($safe->metakey) or empty($safe->metadesc)) {
                    parseMeta::instance($safe->body);
                    if (empty($safe->metakey)) {
                        $datax['metakey'] = parseMeta::get_keywords();
                    }
                    if (empty($safe->metadesc)) {
                        $datax['metadesc'] = parseMeta::metaText($safe->body);
                    }
                }
                
                //process thumb
                $row = Db::Go()->select(self::lTable, array("thumb", "file"))->where("id", Filter::$id, "=")->first()->run();
                if (!empty($_FILES['thumb']['name'])) {
                    $thumbpath = UPLOADS . "/listings/";
                    $tresult = File::process($thumb, $thumbpath, false);
                    
                    File::deleteFile($thumbpath . $row->thumb);
                    File::deleteFile($thumbpath . 'thumbs/' . $row->thumb);
                    try {
                        $img = new Image($thumbpath . $tresult['fname']);
                        $img->bestFit(self::THUMBW, self::THUMBH)->save($thumbpath . 'thumbs/' . $tresult['fname']);
                    } catch (exception $e) {
                        Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                    }
                    $datax['thumb'] = $tresult['fname'];
                }
                
                //process file
                if (!empty($_FILES['file']['name'])) {
                    $fresult = File::process($file, UPLOADS . "/listings/files/", false);
                    File::deleteFile(UPLOADS . "/listings/files/" . $row->file);
                    $datax['file'] = $fresult['fname'];
                }
                if (Validator::post('remfile')) {
                    $datax['file'] = "NULL";
                }
                
                Db::Go()->update(self::lTable, array_merge($data, $datax))->where("id", Filter::$id, "=")->run();
                
                $message = Message::formatSuccessMessage($datax['title'], Lang::$word->LST_UPDATED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
                
                //Add to listings info
                $make_name = Db::Go()->select(Content::mkTable, array("name"))->where("id", $data['make_id'], "=")->one()->run();
                $category_name = Db::Go()->select(Content::ctTable, array("name"))->where("id", $data['category'], "=")->one()->run();
                $location_name = Db::Go()->select(Content::lcTable)->where("id", $data['location'], "=")->first()->run();
                
                $idata = array(
                    'make_name' => $make_name,
                    'make_slug' => Url::doSeo($make_name),
                    'model_name' => Db::Go()->select(Content::mdTable, array("name"))->where("id", $data['model_id'], "=")->one()->run(),
                    'location_name' => json_encode($location_name),
                    'location_slug' => Url::doSeo($location_name->name_slug),
                    'condition_name' => Db::Go()->select(Content::cdTable, array("name"))->where("id", $data['vcondition'], "=")->one()->run(),
                    'category_name' => $category_name,
                    'category_slug' => Url::doSeo($category_name),
                    'fuel_name' => Db::Go()->select(Content::fuTable, array("name"))->where("id", $data['fuel'], "=")->one()->run(),
                    'trans_name' => Db::Go()->select(Content::trTable, array("name"))->where("id", $data['transmission'], "=")->one()->run(),
                    'color_name' => $safe->color_e,
                    'feature_name' => Db::Go()->select(Content::fTable, array("name"))->where("id", Utility::implodeFields($safe->features), "IN")->orderBy("sorting", "ASC")->run('json'),
                    'price' => $safe->price,
                    'year' => $safe->year,
                    'mileage' => $safe->mileage,
                    'special' => empty($safe->price_sale) ? 0 : 1
                );
                
                Db::Go()->update(self::liTable, $idata)->where("listing_id", Filter::$id, "=")->run();
                
                // Add to core
                Items::doCalc();
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Items::_addItem()
         *
         * @param $validate
         * @return void
         */
        public function _addItem($validate)
        {
            $thumb = File::upload("thumb", self::MAXIMG, "png,jpg,jpeg,webp");
            $file = File::upload("file", self::MAXFILE, self::FILES);
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $mid = self::doTitle($safe->model_id);
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'slug' => (empty($safe->slug)) ? $safe->year . '-' . $mid : Url::doSeo($safe->slug),
                    'nice_title' => ucwords(str_replace("-", " ", $mid)),
                    'idx' => Utility::randNumbers(),
                    'location' => $safe->location,
                    'stock_id' => $safe->stock_id,
                    'vin' => $safe->vin,
                    'make_id' => $safe->make_id,
                    'model_id' => $safe->model_id,
                    'year' => $safe->year,
                    'vcondition' => $safe->vcondition,
                    'category' => $safe->category,
                    'mileage' => $safe->mileage,
                    'torque' => $safe->torque,
                    'price' => $safe->price,
                    'price_sale' => $safe->price_sale,
                    'color_e' => $safe->color_e,
                    'color_i' => $safe->color_i,
                    'doors' => $safe->doors,
                    'fuel' => $safe->fuel,
                    'drive_train' => $safe->drive_train,
                    'engine' => $safe->engine,
                    'transmission' => $safe->transmission,
                    'top_speed' => $safe->top_speed,
                    'horse_power' => $safe->horse_power,
                    'features' => json_encode($safe->features),
                    'towing_capacity' => $safe->towing_capacity,
                    'body' => $safe->body ? Url::in_url($safe->body) : "",
                    'metakey' => $safe->metakey,
                    'metadesc' => $safe->metadesc,
                    'notes' => $safe->notes,
                    'expire' => $safe->expire_submit . ' 23:59:59',
                    'status' => isset($_POST['status']) ? 1 : 0,
                    'sold' => isset($_POST['sold']) ? 1 : 0,
                    'is_owner' => $safe->is_owner,
                    'featured' => isset($_POST['featured']) ? 1 : 0
                );
                $datax['title'] = (empty($safe->title)) ? str_replace("-", " ", $data['slug']) : $safe->title;
                $temp_id = App::Session()->get("imgtoken");
                
                File::makeDirectory(UPLOADS . "/listings/pics" . $temp_id . "/thumbs");
                
                //process thumb
                if (!empty($_FILES['thumb']['name'])) {
                    $thumbpath = UPLOADS . "/listings/";
                    $tresult = File::process($thumb, $thumbpath, false);
                    try {
                        $img = new Image($thumbpath . $tresult['fname']);
                        $img->bestFit(self::THUMBW, self::THUMBH)->save($thumbpath . 'thumbs/' . $tresult['fname']);
                    } catch (exception $e) {
                        Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                    }
                    $datax['thumb'] = $tresult['fname'];
                }
                
                //process file
                if (!empty($_FILES['file']['name'])) {
                    $fresult = File::process($file, UPLOADS . "/listings/files/", false);
                    $datax['file'] = $fresult['fname'];
                }
                
                $last_id = Db::Go()->insert(self::lTable, array_merge($data, $datax))->run();
                
                //Add to listings info
                $make_name = Db::Go()->select(Content::mkTable, array("name"))->where("id", $data['make_id'], "=")->one()->run();
                $category_name = Db::Go()->select(Content::ctTable, array("name"))->where("id", $data['category'], "=")->one()->run();
                $location_name = Db::Go()->select(Content::lcTable)->where("id", $data['location'], "=")->first()->run();
                
                $idata = array(
                    'listing_id' => $last_id,
                    'make_name' => $make_name,
                    'make_slug' => Url::doSeo($make_name),
                    'model_name' => Db::Go()->select(Content::mdTable, array("name"))->where("id", $data['model_id'], "=")->one()->run(),
                    'location_name' => json_encode($location_name),
                    'location_slug' => Url::doSeo($location_name->name_slug),
                    'condition_name' => Db::Go()->select(Content::cdTable, array("name"))->where("id", $data['vcondition'], "=")->one()->run(),
                    'category_name' => $category_name,
                    'category_slug' => Url::doSeo($category_name),
                    'fuel_name' => Db::Go()->select(Content::fuTable, array("name"))->where("id", $data['fuel'], "=")->one()->run(),
                    'trans_name' => Db::Go()->select(Content::trTable, array("name"))->where("id", $data['transmission'], "=")->one()->run(),
                    'color_name' => $safe->color_e,
                    'feature_name' => Db::Go()->select(Content::fTable, array("name"))->where("id", Utility::implodeFields($safe->features), "IN")->orderBy("sorting", "ASC")->run('json'),
                    'price' => $safe->price,
                    'year' => $safe->year,
                    'mileage' => $safe->mileage,
                    'special' => empty($safe->price_sale) ? 0 : 1
                );
                
                Db::Go()->insert(self::liTable, $idata)->run();
                
                //process gallery
                if ($rows = Db::Go()->select(self::gTable, array("id", "listing_id"))->where("listing_id", $temp_id, "=")->run()) {
                    $query = "UPDATE `" . self::gTable . "` SET `listing_id` = CASE ";
                    $idlist = '';
                    foreach ($rows as $item):
                        $query .= " WHEN id = " . $item->id . " THEN " . $last_id;
                        $idlist .= $item->id . ',';
                    endforeach;
                    $idlist = substr($idlist, 0, -1);
                    $query .= "
						  END
						  WHERE id IN (" . $idlist . ")";
                    Db::Go()->rawQuery($query)->run();
                    
                    $images = Db::Go()->select(self::gTable)->where("listing_id", $last_id, "=")->orderBy("sorting", "ASC")->run('json');
                    Db::Go()->update(self::lTable, array("gallery" => $images))->where("id", $last_id, "=")->run();
                }
                
                //rename temp folder
                File::renameDirectory(UPLOADS . "/listings/pics" . $temp_id, UPLOADS . "/listings/pics" . $last_id);
                
                // Add to core
                Items::doCalc();
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($datax['title'], Lang::$word->LST_ADDED);
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = $message;
                    $json['redirect'] = Url::url("/admin/items");
                } else {
                    $json['type'] = "alert";
                    $json['title'] = Lang::$word->ALERT;
                    $json['message'] = Lang::$word->NOPROCCESS;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Items::Gallery()
         *
         * @param mixed $id
         * @return void
         */
        public function Gallery($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->GAL_TITLE;
            $tpl->crumbs = ['admin', 'items', 'images'];
            
            if (!$row = Db::Go()->select(self::lTable, array("id", "nice_title"))->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [items.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->data = Db::Go()->select(self::gTable)->where("listing_id", $id, "=")->orderBy("sorting", "ASC")->run();
                $tpl->template = 'admin/items.tpl.php';
            }
        }
        
        /**
         * Items::Stats()
         *
         * @param mixed $id
         * @return void
         */
        public function Stats($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->GAL_TITLE;
            $tpl->crumbs = ['admin', 'items', 'images'];
            
            if (!$row = Db::Go()->select(self::lTable, array("id", "nice_title"))->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [items.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/items.tpl.php';
            }
        }
        
        /**
         * Items::Print()
         *
         * @param int $id
         * @return void
         */
        public function Print($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->GAL_TITLE;
            $tpl->crumbs = ['admin', 'items', 'images'];
            
            if (!$row = Db::Go()->select(self::lTable, array("id", "thumb", "nice_title", "features", "gallery"))->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [items.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->data = $this->listingPreview($id);
                $tpl->features = $this->listingFeatures($tpl->row->features);
                $tpl->gallery = Utility::jSonToArray($tpl->row->gallery);
                $tpl->location = Utility::jSonToArray($tpl->data->location_name);
                $tpl->template = 'admin/items.tpl.php';
            }
        }
        
        /**
         * Items::Pending()
         *
         * @return void
         */
        public function Pending()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LST_TITLE5;
            
            $pager = Paginator::instance();
            $pager->items_total = Db::Go()->count(self::lTable)->where("status", 1, "<")->run();
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                l.*,
                cd.name AS cdname,
                ct.name AS ctname,
                u.username
              FROM
                `" . self::lTable . "` AS l
                LEFT JOIN `" . Content::cdTable . "` AS cd
                  ON cd.id = l.vcondition
                LEFT JOIN `" . Content::ctTable . "` AS ct
                  ON ct.id = l.category
                LEFT JOIN `" . Users::mTable . "` AS u
                  ON u.id = l.user_id
              WHERE status = ?
              ORDER BY created DESC" . $pager->limit;
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', 'items', Lang::$word->LST_TITLE];
            $tpl->title = Lang::$word->LST_TITLE;
            $tpl->data = Db::Go()->rawQuery($sql, array("< 1"))->run();
            $tpl->pager = $pager;
            
            $tpl->template = 'admin/items.tpl.php';
        }
        
        /**
         * Items::Expired()
         *
         * @return void
         */
        public function Expired()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LST_TITLE6;
            
            $pager = Paginator::instance();
            $pager->items_total = Db::Go()->count(self::lTable, "WHERE DATE(expire) < DATE(NOW())")->run();
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                l.*,
                cd.name AS cdname,
                ct.name AS ctname,
                u.username
              FROM
                `" . self::lTable . "` AS l
                LEFT JOIN `" . Content::cdTable . "` AS cd
                  ON cd.id = l.vcondition
                LEFT JOIN `" . Content::ctTable . "` AS ct
                  ON ct.id = l.category
                LEFT JOIN `" . Users::mTable . "` AS u
                  ON u.id = l.user_id
              WHERE DATE(expire) < DATE(NOW())
              ORDER BY created DESC" . $pager->limit;
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', 'items', Lang::$word->LST_TITLE6];
            $tpl->title = Lang::$word->LST_TITLE6;
            $tpl->data = Db::Go()->rawQuery($sql)->run();
            $tpl->pager = $pager;
            
            $tpl->template = 'admin/items.tpl.php';
        }
        
        /**
         * Items::approveListing()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function approveListing()
        {
            $validate = Validator::Run($_POST);
            $validate->set("id", "ID")->required()->numeric();
            
            if (!$item = Db::Go()->select(self::lTable)->where("id", Filter::$id, "=")->where("status", 1, "<")->first()->run()) {
                Message::$msgs['id'] = Lang::$word->SYSTEM_ERR1;
            }
            
            if (empty(Message::$msgs)) {
                $row = Db::Go()->select(Users::mTable, array("email", "membership_id", "CONCAT(fname,' ',lname) as name"))->where("id", $item->user_id, "=")->first()->run();
                $data = array(
                    'status' => 1,
                    'rejected' => 0,
                    'expire' => Content::calculateDays($row->membership_id)
                );
                Db::Go()->update(self::lTable, $data)->where("id", Filter::$id, "=")->run();
                
                $count = Db::Go()->count(self::lTable)->where("user_id", $item->user_id, "=")->where("status", 1, "=")->run();
                Db::Go()->update(Users::mTable, array("listings" => $count))->where("id", $item->user_id, "=")->run();
                Db::Go()->update(self::liTable, array("lstatus" => 1))->where("id", Filter::$id, "=")->run();
                
                //Add to core
                self::doCalc();
                
                //User Email Notification
                $core = App::Core();
                $mailer = Mailer::sendMail();
                $subject = str_replace("[NAME]", $item->nice_title, Lang::$word->LST_APPROVED);
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Listing_Approve.tpl.php');
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[FULLNAME]',
                    '[URL]',
                    '[TITLE]',
                    '[IDX]',
                    '[DATE]',
                    '[COMPANY]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'), array(
                    Utility::getLogo(),
                    $row->name,
                    Url::url("/listing/" . $item->idx, $item->slug),
                    $item->nice_title,
                    $item->idx,
                    date('Y'),
                    $core->company,
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->name);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                
                if ($mailer->send()) {
                    Message::msgModalReply(true, 'success', str_replace("[NAME]", $item->nice_title, Lang::$word->LST_APPROVED), "");
                } else {
                    Message::msgModalReply(true, 'error', $mailer->ErrorInfo, "");
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Items::rejectListing()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function rejectListing()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("id", "ID")->required()->numeric()
                ->set("reason", Lang::$word->LST_REASON)->required()->string();
            
            if (!$item = Db::Go()->select(self::lTable)->where("id", Filter::$id, "=")->where("status", 1, "<")->first()->run()) {
                Message::$msgs['id'] = Lang::$word->SYSTEM_ERR1;
            }
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $row = Db::Go()->select(Users::mTable, array("email", "membership_id", "CONCAT(fname,' ',lname) as name"))->where("id", $item->user_id, "=")->first()->run();
                
                File::deleteFile(UPLOADS . "/listings/" . $item->thumb);
                File::deleteFile(UPLOADS . "/listings/thumbs/" . $item->thumb);
                
                Db::Go()->delete(Items::lTable)->where("id", Filter::$id, "=")->run();
                Db::Go()->delete(Items::liTable)->where("listing_id", Filter::$id, "=")->run();
                Db::Go()->delete(Items::gTable)->where("listing_id", Filter::$id, "=")->run();
                
                $pics = UPLOADS . "/listings/pics" . Filter::$id;
                File::deleteRecursive($pics, true);
                
                //User Email Notification
                $core = App::Core();
                $mailer = Mailer::sendMail();
                $subject = str_replace("[NAME]", $item->nice_title, Lang::$word->LST_REJECTED);
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Listing_Reject.tpl.php');
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[FULLNAME]',
                    '[REASON]',
                    '[TITLE]',
                    '[IDX]',
                    '[DATE]',
                    '[COMPANY]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'), array(
                    Utility::getLogo(),
                    $row->name,
                    $safe->reason,
                    $item->nice_title,
                    $item->idx,
                    date('Y'),
                    $core->company,
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->name);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                
                if ($mailer->send()) {
                    Message::msgModalReply(true, 'success', str_replace("[NAME]", $item->nice_title, Lang::$word->LST_REJECTED), "");
                } else {
                    Message::msgModalReply(true, 'error', $mailer->ErrorInfo, "");
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Items::Listings()
         *
         * @return void
         */
        public function Listings()
        {
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->template = 'front/themes/' . $core->theme . '/listings.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->LISTINGS];
            $tpl->title = Lang::$word->LISTINGS . ' - ' . $core->company;
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            
            
            if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
                list($sort, $order) = explode("|", $_GET['order']);
                $sort = Validator::sanitize($sort, "alphalow", 8);
                $order = Validator::sanitize($order, "alphahi", 4);
                if (in_array($sort, array(
                    "year",
                    "price",
                    "make",
                    "model",
                    "mileage"))) {
                    $ord = ($order == 'DESC') ? " DESC" : " ASC";
                    $sorting = str_replace(array("make", "model"), array("make_id", "model_id"), $sort) . $ord;
                } else {
                    $sorting = " created DESC";
                }
            } else {
                $sorting = " created DESC";
            }
            
            $and = null;
            $parts = parse_url($_SERVER['REQUEST_URI']);
            isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
            
            $required = array(
                "condition" => 0,
                "make" => 1,
                "color" => 2,
                "body" => 3,
                "sale" => 4,
                "price_min" => 5,
                "price_max" => 6,
                "year_min" => 7,
                "year_max" => 8,
                "miles_min" => 9,
                "miles_max" => 10
            );
            
            if (array_intersect_key($qs, $required)) {
                if (!empty($qs['condition'])) {
                    if ($condition = Validator::sanitize(strtolower($qs['condition']), "alpha", 8)) {
                        $and .= " AND li.condition_name = '$condition'";
                    }
                }
                if (!empty($qs['make'])) {
                    if ($make = Validator::sanitize(strtolower($qs['make']), "db", 20)) {
                        $and .= " AND li.make_slug = '$make'";
                    }
                }
                if (!empty($qs['color'])) {
                    if ($color = Validator::sanitize(strtolower($qs['color']), "alpha", 20)) {
                        $and .= " AND li.color_name = '$color'";
                    }
                }
                if (!empty($qs['body'])) {
                    if ($body = Validator::sanitize(strtolower($qs['body']), "db", 25)) {
                        $and .= " AND li.category_slug = '$body'";
                    }
                }
                if (!empty($qs['sale'])) {
                    if (Validator::sanitize(strtolower($qs['sale']), "alpha", 3)) {
                        $and .= " AND li.special = 1";
                    }
                }
                
                if (!empty($qs['year_min']) and !empty($qs['year_max'])) {
                    $year_min = Validator::sanitize($qs['year_min'], "int", 4);
                    $year_max = Validator::sanitize($qs['year_max'], "int", 4);
                    if ($year_min and $year_max) {
                        $and .= " AND l.year BETWEEN '$year_min' AND '$year_max'";
                    }
                } else {
                    if (!empty($qs['year_min'])) {
                        if ($year_min = Validator::sanitize($qs['year_min'], "int", 4)) {
                            $and .= " AND l.year = '$year_min'";
                        }
                    }
                    if (!empty($qs['year_max'])) {
                        if ($year_max = Validator::sanitize($qs['year_max'], "int", 4)) {
                            $and .= " AND l.year = '$year_max'";
                        }
                    }
                }
                
                if (!empty($qs['price_min']) and !empty($qs['price_max'])) {
                    $price_min = Validator::sanitize($qs['price_min'], "float");
                    $price_max = Validator::sanitize($qs['price_max'], "float");
                    if ($price_min and $price_max) {
                        $and .= " AND l.price BETWEEN '$price_min' AND '$price_max'";
                    }
                } else {
                    if (!empty($qs['price_min'])) {
                        if ($price_min = Validator::sanitize($qs['price_min'], "float")) {
                            $and .= " AND l.price = '$price_min'";
                        }
                    }
                    if (!empty($qs['price_max'])) {
                        if ($price_max = Validator::sanitize($qs['price_max'], "float")) {
                            $and .= " AND l.price = '$price_max'";
                        }
                    }
                }
                
                if (!empty($qs['miles_min']) and !empty($qs['miles_max'])) {
                    $miles_min = Validator::sanitize($qs['miles_min'], "int");
                    $miles_max = Validator::sanitize($qs['miles_max'], "int");
                    if ($miles_min and $miles_max) {
                        $and .= " AND l.mileage BETWEEN '$miles_min' AND '$miles_max'";
                    }
                    
                } else {
                    if (!empty($qs['miles_min'])) {
                        if ($miles_min = Validator::sanitize($qs['miles_min'], "int")) {
                            $and .= " AND l.mileage = '$miles_min'";
                        }
                    }
                    if (!empty($qs['miles_max'])) {
                        if ($miles_max = Validator::sanitize($qs['miles_max'], "int")) {
                            $and .= " AND l.mileage = '$miles_max'";
                        }
                    }
                }
                
                //$sqlCount = "SELECT COUNT(li.id) as id FROM `" . self::lTable . "` l LEFT JOIN `" . self::liTable . "` li ON l.id = li.listing_id WHERE li.lstatus = 1 $and;";
                $total = Db::Go()->count(self::lTable, "AS l LEFT JOIN `" . self::liTable . "` li ON l.id = li.listing_id WHERE li.lstatus = 1 $and")->run();
            } else {
                $total = Db::Go()->count(self::lTable)->where("status", 1, "=")->run();
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $total;
            $pager->default_ipp = $core->perpage;
            $pager->paginate();
            
            $sql = "
              SELECT 
                li.model_name,
                li.condition_name,
                li.category_name,
                li.trans_name,
                li.fuel_name,
                li.color_name,
				l.id,
                l.thumb,
                l.idx,
				l.doors,
                l.nice_title,
				l.gallery,
                l.title,
                l.slug,
                l.price,
                l.price_sale,
                l.year,
                l.sold,
				l.engine,
				l.top_speed,
                l.created,
                l.mileage,
                l.featured
              FROM
                `" . self::lTable . "` AS l 
                LEFT JOIN `" . self::liTable . "` AS li 
                  ON l.id = li.listing_id 
              WHERE l.status = ?
			  $and
              ORDER BY $sorting" . $pager->limit;
            
            $tpl->data = Db::Go()->rawQuery($sql, array(1))->run();
            $tpl->pager = $pager;
            $tpl->qs = $qs;
            $tpl->parts = $parts;
            
            $tpl->template = 'front/themes/' . $core->theme . '/listings.tpl.php';
        }
        
        /**
         * Items::Search()
         *
         * @return void
         */
        public function Search()
        {
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->SRCH];
            $tpl->title = Lang::$word->SRCH . ' - ' . $core->company;
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/search.tpl.php';
            
            if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
                list($sort, $order) = explode("|", $_GET['order']);
                $sort = Validator::sanitize($sort, "alphalow", 8);
                $order = Validator::sanitize($order, "alphahi", 4);
                if (in_array($sort, array(
                    "year",
                    "price",
                    "make",
                    "model",
                    "mileage"))) {
                    $ord = ($order == 'DESC') ? " DESC" : " ASC";
                    $sorting = str_replace(array("make", "model"), array("make_id", "model_id"), $sort) . $ord;
                } else {
                    $sorting = " created DESC";
                }
            } else {
                $sorting = " created DESC";
            }
            
            $and = null;
            $parts = parse_url($_SERVER['REQUEST_URI']);
            isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
            
            $required = array(
                "condition" => 0,
                "make_id" => 1,
                "model_id" => 2,
                "color" => 3,
                "transmission" => 4,
                "body" => 5,
                "fuel" => 6,
                "price" => 7,
                "year" => 8,
                "miles" => 9,
            );
            
            if (array_intersect_key($qs, $required)) {
                if (!empty($qs['make_id'])) {
                    if ($make = Validator::sanitize($qs['make_id'], "int", 8)) {
                        $and .= " AND l.make_id = $make";
                    }
                }
                if (!empty($qs['model_id'])) {
                    if ($model = Validator::sanitize($qs['model_id'], "int", 8)) {
                        $and .= " AND l.model_id = $model";
                    }
                }
                if (!empty($qs['transmission'])) {
                    if ($trans = Validator::sanitize($qs['transmission'], "int", 4)) {
                        $and .= " AND l.transmission = $trans";
                    }
                }
                if (!empty($qs['color'])) {
                    if ($color = Validator::sanitize(strtolower($qs['color']), "alpha", 20)) {
                        $and .= " AND li.color_name = '$color'";
                    }
                }
                if (!empty($qs['body'])) {
                    if ($body = Validator::sanitize(strtolower($qs['body']), "int", 4)) {
                        $and .= " AND li.category = '$body'";
                    }
                }
                if (!empty($qs['condition'])) {
                    if ($condition = Validator::sanitize(strtolower($qs['condition']), "int", 3)) {
                        $and .= " AND li.vcondition = '$condition'";
                    }
                }
                if (!empty($qs['year'])) {
                    if ($year = Validator::sanitize($qs['year'], "int", 4)) {
                        $and .= " AND l.year = $year";
                    }
                }
                if (!empty($qs['fuel'])) {
                    if ($fuel = Validator::sanitize($qs['fuel'], "int", 3)) {
                        $and .= " AND l.fuel = $fuel";
                    }
                }
                if (!empty($qs['price'])) {
                    if ($price = Validator::sanitize($qs['price'], "int", 2)) {
                        switch ($price) {
                            case 10 :
                                $and .= " AND l.price BETWEEN 0 AND 5000";
                                break;
                            case 20 :
                                $and .= " AND l.price BETWEEN 5000 AND 10000";
                                break;
                            case 30 :
                                $and .= " AND l.price BETWEEN 10000 AND 20000";
                                break;
                            case 40 :
                                $and .= " AND l.price BETWEEN 20000 AND 30000";
                                break;
                            case 50 :
                                $and .= " AND l.price BETWEEN 30000 AND 50000";
                                break;
                            case 60 :
                                $and .= " AND l.price BETWEEN 50000 AND 75000";
                                break;
                            case 70 :
                                $and .= " AND l.price BETWEEN 75000 AND 100000";
                                break;
                            case 80 :
                                $and .= " AND l.price BETWEEN 100000 AND 9999999";
                                break;
                        }
                    }
                }
                if (!empty($qs['miles'])) {
                    if ($miles = Validator::sanitize($qs['miles'], "int", 2)) {
                        switch ($miles) {
                            case 10 :
                                $and .= " AND l.mileage BETWEEN 0 AND 10000";
                                break;
                            case 20 :
                                $and .= " AND l.mileage BETWEEN 10000 AND 30000";
                                break;
                            case 30 :
                                $and .= " AND l.mileage BETWEEN 30000 AND 60000";
                                break;
                            case 40 :
                                $and .= " AND l.mileage BETWEEN 60000 AND 100000";
                                break;
                            case 50 :
                                $and .= " AND l.mileage BETWEEN 100000 AND 150000";
                                break;
                            case 60 :
                                $and .= " AND l.mileage BETWEEN 150000 AND 200000";
                                break;
                            case 70 :
                                $and .= " AND l.mileage BETWEEN 200000 AND 9999999";
                                break;
                        }
                    }
                }
                
                $total = Db::Go()->count(self::lTable, "AS l LEFT JOIN `" . self::liTable . "` li ON l.id = li.listing_id WHERE li.lstatus = 1 $and")->run();
            } else {
                $total = Db::Go()->count(self::lTable)->where("status", 1, "=")->run();
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $total;
            $pager->default_ipp = $core->sperpage;
            $pager->paginate();
            
            $sql = "
              SELECT 
                li.model_name,
                li.condition_name,
                li.category_name,
                li.trans_name,
                li.fuel_name,
                li.color_name,
				l.id,
                l.thumb,
                l.idx,
				l.doors,
                l.nice_title,
                l.title,
				l.gallery,
                l.slug,
                l.price,
                l.price_sale,
                l.year,
                l.sold,
				l.engine,
				l.top_speed,
                l.created,
                l.mileage,
                l.featured,
				li.feature_name,
				li.location_name 
              FROM
                `" . self::lTable . "` AS l 
                LEFT JOIN `" . self::liTable . "` AS li 
                  ON l.id = li.listing_id 
              WHERE li.lstatus = ? 
			  $and
              ORDER BY $sorting" . $pager->limit . ";
              ";
            
            $tpl->data = Db::Go()->rawQuery($sql, array(1))->run();
            $tpl->categories = Db::Go()->select(Content::ctTable, array("id", "name"))->run();
            
            $tpl->advert = ($core->show_news) ? Db::Go()->rawQuery("SELECT * FROM `" . Content::nwaTable . "` ORDER BY RAND() LIMIT 1")->first()->run() : null;
            //$tpl->advert = ($core->show_news) ? Db::Go()->select(Content::nwaTable)->random()->run() : null;
            
            $tpl->pager = $pager;
            $tpl->qs = $qs;
            $tpl->parts = $parts;
            
            $tpl->template = 'front/themes/' . $core->theme . '/search.tpl.php';
        }
        
        /**
         * Items::Render()
         *
         * @param $idx
         * @param $slug
         * @return void
         */
        public function Render($idx, $slug)
        {
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->title = Lang::$word->SRCH . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
            
            $sql = "
                SELECT
                   li.model_name,
                   li.condition_name,
                   li.category_name,
                   li.trans_name,
                   li.location_name,
                   li.fuel_name,
                   li.fuel_name,
                   li.make_slug,
                   li.category_slug,
                   li.fuel_name,
                   li.make_name,
                   li.feature_name,
                   u.username,
                   u.avatar,
                   l.*
                FROM
                   `" . self::lTable . "` AS l
                   LEFT JOIN
                      `" . self::liTable . "` AS li
                      ON li.listing_id = l.id
                   LEFT JOIN
                      `" . Users::mTable . "` AS u
                      ON u.id = l.user_id
                WHERE
                   l.status = ?
                   AND l.idx = ?
                   AND l.slug = ?
            ";
            
            if (!$tpl->row = Db::Go()->rawQuery($sql, array(1, $idx, $slug))->first()->run()) {
                if (DEBUG) {
                    $tpl->template = 'admin/error.tpl.php';
                    $tpl->error = "Invalid slug ($slug) detected [Items.class.php, ln.:" . __line__ . "]";
                } else {
                    $tpl->template = 'front/themes/' . $core->theme . '/404.tpl.php';
                }
            } else {
                $tpl->title = Url::formatMeta($tpl->row->nice_title);
                $tpl->keywords = $tpl->row->metakey;
                $tpl->description = $tpl->row->metadesc;
                
                $tpl->meta = '
                  <meta property="og:type" content="article" />
                  <meta property="og:title" content="' . $tpl->row->nice_title . '" />
                  <meta property="og:image" content="' . UPLOADURL . '/listings/thumbs/' . $tpl->row->thumb . '" />
                  <meta property="og:description" content="' . $tpl->nice_title . '" />
                  <meta property="og:url" content="' . Url::url('/listing/' . $idx, $slug) . '" />
			    ';
                $tpl->gallery = Utility::jSonToArray($tpl->row->gallery);
                $tpl->features = Utility::jSonToArray($tpl->row->feature_name);
                $tpl->location = Utility::jSonToArray($tpl->row->location_name);
                $tpl->category = Utility::jSonToArray($core->category_list);
                $tpl->makes = Utility::jSonToArray($core->makes);
                
                Stats::updateHits($tpl->row->id);
                $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), array(0 => $tpl->row->make_slug, 1 => "listings/?make=" . $tpl->row->make_slug), $tpl->row->nice_title];
                $tpl->template = 'front/themes/' . $core->theme . '/listing.tpl.php';
            }
        }
        
        /**
         * Items::Seller()
         *
         * @param $slug
         * @return void
         */
        public function Seller($slug)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . App::Core()->theme . "/";
            $tpl->title = str_replace("[COMPANY]", App::Core()->company, Lang::$word->META_WELCOME);
            $tpl->keywords = null;
            $tpl->description = null;
            $core = App::Core();
            
            if (!$row = Db::Go()->select(Content::lcTable)->where("name_slug", $slug, "=")->first()->run()) {
                $tpl->template = 'front/themes/' . App::Core()->theme . '/404.tpl.php';
                if (DEBUG) {
                    Debug::AddMessage("errors", '<i>ERROR</i>', "Invalid page detected [items.class.php, ln.:" . __line__ . "] slug ['<b>" . $slug . "</b>']");
                }
            } else {
                $tpl->row = $row;
                
                $total = Db::Go()->count(self::lTable)->where("status", 1, "=")->where("location", $row->id, "=")->run();
                $pager = Paginator::instance();
                $pager->items_total = $total;
                $pager->default_ipp = $core->sperpage;
                $pager->paginate();
                
                $sql = "
                    SELECT
                       li.model_name,
                       li.condition_name,
                       li.category_name,
                       li.trans_name,
                       li.fuel_name,
                       li.color_name,
                       l.id,
                       l.thumb,
                       l.idx,
                       l.doors,
                       l.nice_title,
                       l.title,
                       l.gallery,
                       l.slug,
                       l.price,
                       l.price_sale,
                       l.year,
                       l.sold,
                       l.engine,
                       l.top_speed,
                       l.created,
                       l.mileage,
                       l.featured
                    FROM
                       `" . self::liTable . "` AS li
                       LEFT JOIN
                          `" . self::lTable . "` AS l
                          ON l.id = li.listing_id
                    WHERE
                       l.status = ?
                       AND l.location = ?
                    ORDER BY
                       l.featured DESC,
                       l.created DESC " . $pager->limit;
                
                $tpl->data = Db::Go()->rawQuery($sql, array(1, $row->id))->run();
                $tpl->pager = $pager;
                $tpl->category = Utility::jSonToArray($core->category_list);
                $tpl->makes = Utility::jSonToArray($core->makes);
                
                $tpl->title = Url::formatMeta($row->name);
                $tpl->keywords = $core->metakeys;
                $tpl->description = $core->metadesc;
                $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), $row->name];
                $tpl->template = 'front/themes/' . $core->theme . '/seller.tpl.php';
            }
        }
        
        /**
         * Items::listingPreview()
         *
         * @param int $id
         * @return int|mixed
         */
        public function listingPreview($id)
        {
            
            $sql = "
              SELECT
                l.*,
                li.*,
                l.id AS id,
                CONCAT(li.make_name, ' ', li.model_name) AS name
              FROM
                `" . self::lTable . "` AS l
                LEFT JOIN `" . self::liTable . "` AS li
                  ON li.listing_id = l.id
              WHERE li.listing_id = ?";
            
            $row = Db::Go()->rawQuery($sql, array($id))->first()->run();
            return ($row) ?: 0;
        }
        
        /**
         * Items::listingFeatures()
         *
         * @param string $ids
         * @return array|int|string
         */
        public function listingFeatures($ids)
        {
            $result = ($ids) ? Utility::implodeFields(json_decode($ids)) : 0;
            
            $sql = "SELECT * FROM `" . Content::fTable . "` WHERE id IN(" . $result . ") ORDER BY sorting";
            $row = Db::Go()->rawQuery($sql)->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Items::getBrands()
         *
         * @return array|int|string
         */
        public function getBrands()
        {
            $sql = "
              SELECT
                m.name,
                m.id,
                COUNT(m.id) as items
              FROM
                `" . Content::mkTable . "` AS m
                INNER JOIN `" . self::lTable . "` AS l
                  ON l.make_id = m.id
              WHERE l.status = ?
              GROUP BY m.id
              ORDER BY RAND()
              LIMIT 8";
            
            $row = Db::Go()->rawQuery($sql, array(1))->run();
            return ($row) ?: 0;
        }
        
        /**
         * Items::doCalc()
         *
         * @return void
         */
        public static function doCalc()
        {
            $sql = array(
                "MIN(price) AS minprice",
                "MAX(price) AS maxprice",
                "MIN(price_sale) AS minsprice",
                "MAX(price_sale) AS maxsprice",
                "MIN(YEAR) AS minyear",
                "MAX(YEAR) AS maxyear",
                "MIN(mileage) AS minkm",
                "MAX(mileage) AS maxkm"
            );
            
            $val = Db::Go()->select(self::lTable, $sql)->where("status", 1, "=")->first()->run();
            
            $make = Db::Go()->select(self::liTable, array("make_name", "COUNT(id) as total"))->where("lstatus", 1, "=")->groupBy("make_name")->run("json");
            $category = Db::Go()->select(self::liTable, array("category_name", "COUNT(id) as total"))->where("lstatus", 1, "=")->groupBy("category_name")->run("json");
            $condition = Db::Go()->select(self::liTable, array("condition_name", "COUNT(id) as total"))->where("lstatus", 1, "=")->groupBy("condition_name")->run("json");
            $color = Db::Go()->select(self::lTable, array("color_e", "COUNT(id) as total"))->where("status", 1, "=")->groupBy("color_e")->run("json");
            $year_list = Db::Go()->select(self::lTable, array("year", "COUNT(id) as total"))->where("status", 1, "=")->groupBy("year")->run("json");
            
            $ids = Db::Go()->select(Items::lTable, array("GROUP_CONCAT(make_id) as mkids, GROUP_CONCAT(model_id) as mdids"))->first()->run();
            $makes = Db::Go()->select(Content::mkTable, array("id", "name"))->where("id", explode(",", $ids->mkids), "IN")->groupBy("id")->run("json");
            $models = Db::Go()->select(Content::mdTable, array("id", "name"))->where("id", explode(",", $ids->mdids), "IN")->groupBy("id")->run("json");
            
            // Add to core
            $odata = array(
                'minprice' => $val->minprice,
                'maxprice' => $val->maxprice,
                'minsprice' => $val->minsprice,
                'maxsprice' => $val->maxsprice,
                'minyear' => $val->minyear,
                'maxyear' => $val->maxyear,
                'minkm' => $val->minkm,
                'maxkm' => $val->maxkm,
                'color' => $color,
                'makes' => $make,
                'year_list' => $year_list,
                'cond_list' => $condition,
                'category_list' => $category,
                'make_list' => $makes,
                'model_list' => $models,
            );
            Db::Go()->update(Core::sTable, $odata)->where("id", 1, "=")->run();
        }
        
        /**
         * Items::doTitle()
         *
         * @param $model_id
         * @return array|int|mixed|string|string[]|null
         */
        public static function doTitle($model_id)
        {
            $sql = "
              SELECT
                md.name as mdname, mk.name as mkname
              FROM
                `" . Content::mdTable . "` AS md
                LEFT JOIN `" . Content::mkTable . "` AS mk
                  ON mk.id = md.make_id
              WHERE md.id = ?";
            
            $row = Db::Go()->rawQuery($sql, array($model_id))->first()->run();
            return ($row) ? Url::doSeo($row->mkname . '-' . $row->mdname) : 0;
        }
        
        /**
         * Items::activityIcons()
         *
         * @param mixed $type
         * @return string|void
         */
        public static function activityIcons($type)
        {
            
            switch ($type) {
                case "added":
                    return "car";
                
                case "like":
                    return "hand thumbs up";
                
                case "membership":
                    return "award";
                
                case "login":
                    return "lock";
            }
        }
        
        /**
         * Items::activityTitle()
         *
         * @param mixed $row
         * @return string|void
         */
        public static function activityTitle($row)
        {
            switch ($row->type) {
                case "like":
                    return Lang::$word->LIKE . " &rsaquo; " . $row->title;
                
                case "added":
                    return Lang::$word->ADDED . " &rsaquo; " . $row->title;
                
                case "membership":
                    return Lang::$word->MEMBERSHIP . " &rsaquo; " . $row->title;
                
                case "login":
                    return Lang::$word->LOGIN;
            }
        }
        
        /**
         * Items::activityDesc()
         *
         * @param mixed $row
         * @return string|void
         */
        public static function activityDesc($row)
        {
            switch ($row->type) {
                case "like":
                    return Lang::$word->LST_AC_LIKED . " &rsaquo; " . $row->title;
                
                case "added":
                    return Lang::$word->LST_AC_ADD . " &rsaquo; " . $row->title;
                
                case "membership":
                    return Lang::$word->LST_AC_MEM . " &rsaquo; " . $row->title;
                
                case "login":
                    return Lang::$word->LST_AC_LOGIN;
            }
        }
    }