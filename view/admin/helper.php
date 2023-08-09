<?php
    /**
     * Helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: helper.php, v1.00 2022-02-05 10:12:05 gewa Exp $
     */
    
    use Mpdf\Mpdf;
    use Mpdf\MpdfException;
    
    const _WOJO = true;
    require_once("../../init.php");
    
    if (!App::Auth()->is_Admin())
        exit;
    
    $gAction = Validator::get('action');
    $pAction = Validator::post('action');
    $iAction = Validator::post('iaction');
    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
    
    /* == Post Actions== */
    switch ($pAction) :
        /* == New Model == */
        case "newModel":
            App::Content()->newModel();
            break;
        
        /* == New Inventory == */
        case "newInventory":
            App::Content()->newInventory();
            break;
        
        /* == Update Review == */
        case "editReview":
            App::Content()->editReview();
            break;
        
        /* == Update Role Description == */
        case "editRole":
            App::Users()->updateRoleDescription();
            break;
        
        /* == Change Role == */
        case "changeRole":
            if (Auth::checkAcl("owner")):
                Db::Go()->update(Users::rpTable, array("active" => intval($_POST['active'])))->where("id", Filter::$id, "=")->run();
            endif;
            break;
        
        /* == Approve Listing == */
        case "approveListing":
            App::Items()->approveListing();
            break;
        
        /* == Reject Listing == */
        case "rejectListing":
            App::Items()->rejectListing();
            break;
        
        /* == Update Transmission == */
        case "editTransmission":
            if (Db::Go()->update(Content::trTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $data['trans_list'] = Db::Go()->select(Content::trTable)->orderBy("name", "ASC")->run('json');
                Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
                
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Fuel == */
        case "editFuel":
            if (Db::Go()->update(Content::fuTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $data['fuel_list'] = Db::Go()->select(Content::fuTable)->orderBy("name", "ASC")->run('json');
                Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
                
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Condition == */
        case "editCondition":
            if (Db::Go()->update(Content::cdTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $data['cond_list_alt'] = Db::Go()->select(Content::cdTable)->orderBy("name", "ASC")->run('json');
                Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
                
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Feature == */
        case "editFeature":
            if (Db::Go()->update(Content::fTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Make == */
        case "editMake":
            if (Db::Go()->update(Content::mkTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Model == */
        case "editModel":
            if (Db::Go()->update(Content::mdTable, array("name" => $title))->where("id", Filter::$id, "=")->run()):
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Language Phrase == */
        case "editPhrase":
            if (file_exists(BASEPATH . Lang::langdir . Core::$language . ".lang.xml")):
                $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . ".lang.xml");
                $node = $xmlel->xpath("/language/phrase[@data = '" . $_POST['key'] . "']");
                $node[0][0] = $title;
                $xmlel->asXML(BASEPATH . Lang::langdir . Core::$language . ".lang.xml");
                $json['title'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Update Country Tax == */
        case "editTax":
            if (empty($_POST['title'])):
                print '0.000';
                exit;
            endif;
            $data['vat'] = Validator::sanitize($title, "float");
            Db::Go()->update(Content::cTable, $data)->where("id", Filter::$id, "=")->run();
            
            $json['title'] = $title;
            print json_encode($json);
            break;
        
        /* == Update Gallery Title == */
        case "editGallery":
            if (Db::Go()->update(Items::gTable, array("title" => $title))->where("id", Filter::$id, "=")->run()):
                $json['type'] = "success";
                $json['title'] = $title;
                $json['html'] = $title;
                print json_encode($json);
            endif;
            break;
        
        /* == Chnage Gateway Status == */
        case "gatewayStatus":
            if (Auth::checkAcl("owner")):
                Db::Go()->update(Content::gwTable, array("active" => intval($_POST['active'])))->where("id", Filter::$id, "=")->run();
            endif;
            break;
        
        /* == Gallery Upload == */
        case "uploadGallery":
            if (!empty($_FILES['file']['name'])):
                $dir = UPLOADS . '/listings/pics' . Filter::$id . '/';
                $tdir = UPLOADS . '/listings/pics' . Filter::$id . '/thumbs/';
                $ldir = UPLOADURL . '/listings/pics' . Filter::$id . '/thumbs/';
                
                $upl = Upload::instance(App::Core()->file_size, "png,jpg,jpeg");
                $upl->process("file", $dir, "IMG_");
                
                if (empty(Message::$msgs)):
                    try {
                        $img = new Image($dir . $upl->fileInfo['fname']);
                        $img->crop(Items::THUMBW, Items::THUMBH)->save($tdir . $upl->fileInfo['fname']);
                        
                        $data = array("listing_id" => Filter::$id, "title" => "-/-", "photo" => $upl->fileInfo['fname']);
                        Db::Go()->insert(Items::gTable, $data)->run();
                    } catch (exception $e) {
                        Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                    }
                    $json['filename'] = $ldir . $upl->fileInfo['fname'];
                    $json['type'] = "success";
                else:
                    $json['type'] = "error";
                    $json['filename'] = '';
                    $json['message'] = Message::$msgs['name'];
                endif;
                print json_encode($json);
            endif;
            break;
        
        /* == Process Images == */
        case "processImages":
            $num_files = count($_FILES['images']['tmp_name']);
            $filedir = UPLOADS . '/listings/pics' . Filter::$id;
            File::makeDirectory($filedir . '/thumbs');
            
            for ($x = 0; $x < $num_files; $x++):
                $image = $_FILES['images']['name'][$x];
                $newName = "IMG_" . Utility::randomString(12);
                $ext = substr($image, strrpos($image, '.') + 1);
                $name = $newName . "." . strtolower($ext);
                $fullname = $filedir . '/' . $name;
                
                if (!move_uploaded_file($_FILES['images']['tmp_name'][$x], $fullname)) {
                    die(Message::msgSingleError(Lang::$word->FU_ERROR13, false));
                }
                
                try {
                    $img = new Image($filedir . '/' . $name);
                    $img->crop(Items::THUMBW, Items::THUMBH)->save($filedir . '/thumbs/' . $name);
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                
                $last_id = Db::Go()->insert(Items::gTable, array("listing_id" => Filter::$id, "photo" => $name, "title" => "-/-"))->run();
                print '
                <div class="columns" id="item_' . $last_id . '" data-id="' . $last_id . '">
                  <div class="wojo attached simple fitted segment center aligned"><img src="' . UPLOADURL . '/listings/pics' . Filter::$id . '/thumbs/' . $name . '" alt="" class="wojo rounded image">
                    <div class="wojo buttons middle attached">
                      <a data-set=\'{"option":[{"action":"editGallery","id": ' . $last_id . '}], "label":"' . Lang::$word->EDIT . '", "url":"helper.php", "parent":"#title_' . $last_id . '", "complete":"replace", "modalclass":"normal"}\' class="wojo small icon positive button action"><i class="icon pencil"></i></a>
                      <a data-set=\'{"option":[{"delete": "deleteGallery","title": "-/-","id": ' . $last_id . '}],"action":"delete","parent":"#item_' . $last_id . '"}\' class="wojo small icon negative button data"><i class="icon trash"></i></a>
                    </div>
                  </div>
				  <p class="wojo small demi text" id="title_' . $last_id . '">-/-</p>
                </div>';
            endfor;
            break;
        
        /* == Editor Upload == */
        case "eupload":
            if (!empty($_FILES['file']['name'])):
                $dir = UPLOADS . '/images/';
                $num_files = count($_FILES['file']['tmp_name']);
                $jsons = [];
                $exts = ['image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg'];
                $maxSize = 4194304;
                
                foreach ($_FILES['file']['name'] as $x => $name):
                    $ext = substr(strrchr($_FILES['file']["name"][$x], '.'), 1);
                    $image = $_FILES['file']["name"][$x];
                    if ($_FILES["file"]["size"][$x] > $maxSize):
                        $json['error'] = true;
                        $json['type'] = "error";
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
                        print json_encode($json);
                        exit;
                    endif;
                    
                    $ext = strtolower($_FILES['file']['type'][$x]);
                    if (!in_array($ext, $exts)):
                        $json['error'] = true;
                        $json['type'] = "error";
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR8 . "jpg, png, jpeg"; //invalid extension
                        print json_encode($json);
                        exit;
                    endif;
                    
                    if (file_exists($dir . $image)):
                        $json['error'] = true;
                        $json['type'] = "error";
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR6; //file exists
                        print json_encode($json);
                        exit;
                    endif;
                    
                    if (!getimagesize($_FILES['file']["tmp_name"][$x])):
                        $json['error'] = true;
                        $json['type'] = "error";
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR7; //invalid image
                        print json_encode($json);
                        exit;
                    endif;
                    
                    if (!move_uploaded_file($_FILES['file']['tmp_name'][$x], $dir . $image)):
                        $json['error'] = true;
                        $json['type'] = "error";
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR13; //cant move  image
                        print json_encode($json);
                        exit;
                    endif;
                    
                    if (empty(Message::$msgs)):
                        try {
                            $img = new Image($dir . $image);
                            $img->resizeToWidth(400)->save($dir . "thumbs/" . $image);
                            
                            $jsons['file-' . $x] = array(
                                'url' => UPLOADURL . '/images/' . $image, 'id' => $x
                            );
                        } catch (exception $e) {
                            Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                        }
                    endif;
                endforeach;
                print json_encode($jsons);
            endif;
            break;
    endswitch;
    
    /* == Get Actions== */
    switch ($gAction) :
        /* == Index Payments Chart == */
        case "getIndexStats":
            $data = Stats::indexSalesStats();
            print json_encode($data);
            break;
        
        /* == Main Stats == */
        case "getMainStats":
            $data = Stats::getMainStats();
            print json_encode($data);
            break;
        
        /* == Get Content Type == */
        case "contenttype":
            $type = Validator::sanitize($_GET['type'], "alpha");
            $html = '';
            if ($type == "page"):
                $data = Db::Go()->select(Content::pgTable, array("id", "title"))->where("active", 1, "=")->orderBy("title", "ASC")->run();
                if ($data):
                    foreach ($data as $row):
                        $html .= "<option value=\"" . $row->id . "\">" . $row->title . "</option>\n";
                    endforeach;
                    $json['type'] = 'page';
                endif;
            
            else:
                $json['type'] = 'web';
            endif;
            $json['message'] = $html;
            print json_encode($json);
            break;
        
        /* == Load Language Section == */
        case "loadLanguageSection":
            $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . ".lang.xml");
            $section = $xmlel->xpath('/language/phrase[@section="' . Validator::sanitize($_GET['section']) . '"]');
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->xmlel = $xmlel;
            $tpl->section = $section;
            $tpl->template = 'loadLanguageSection.tpl.php';
            $json['html'] = $tpl->render();
            print json_encode($json);
            break;
        
        /* == Get Email Template == */
        case "getEtemplate":
            $file = Validator::sanitize($_GET['filename']);
            $path = BASEPATH . "/mailer/" . Core::$language . "/" . $file;
            
            if (file_exists($path)):
                $html = file_get_contents($path);
                $json['status'] = 'success';
                $json['title'] = substr(str_replace("_", " ", $file), 0, -8);
                $json['html'] = Url::out_url($html);
            else:
                $json['status'] = 'error';
                $json['message'] = Lang::$word->ET_ERROR;
            endif;
            
            print json_encode($json);
            break;
        
        /* == All Sales Chart == */
        case "getSalesChart":
            $data = Stats::getAllSalesStats();
            print json_encode($data);
            break;
        
        /* == All Sales Export == */
        case "exportAllTransactions":
            header("Pragma: no-cache");
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=AllPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'Item', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
            
            $result = Stats::exportAllTransactions();
            if ($result):
                foreach ($result as $row) :
                    fputcsv($data, $row);
                endforeach;
            endif;
            break;
        
        /* == Package Payments Chart == */
        case "getPackagePaymentsChart":
            $data = Stats::getPackagePaymentsChart(Filter::$id);
            print json_encode($data);
            break;
        
        /* == Export Package Payments == */
        case "exportMembershipPayments":
            header("Pragma: no-cache");
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=PackagePayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
            
            $result = Stats::exportPackagePayments(Filter::$id);
            if ($result):
                foreach ($result as $row) :
                    fputcsv($data, $row);
                endforeach;
            endif;
            break;
        
        /* == Edit Review == */
        case "editReview":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->data = Db::Go()->select(Content::rwTable)->where("id", Filter::$id, "=")->first()->run();
            $tpl->template = 'editReview.tpl.php';
            echo $tpl->render();
            break;
        
        /* == New Model == */
        case "newModel":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->makes = Db::Go()->select(Content::mkTable)->orderBy("name", "ASC")->run('json');
            $tpl->template = 'newModel.tpl.php';
            echo $tpl->render();
            break;
        
        /* == New Inventory == */
        case "newInventory":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->type = Validator::sanitize($_GET['type']);
            $tpl->template = 'newInventory.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Edit Role == */
        case "editRole":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->data = Db::Go()->select(Users::rTable)->where("id", Filter::$id, "=")->first()->run();
            $tpl->template = 'editRole.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Update Role Description == */
        case "userPaymentsChart":
            $data = Stats::userPaymentsChart(Filter::$id);
            print json_encode($data);
            break;
        
        /* == Load Photos == */
        case "loadPhotos":
            if ($rows = Db::Go()->select(Items::gTable)->where("listing_id", Filter::$id, "=")->first()->run()):
                $tpl = App::View(ADMINBASE . '/snippets/');
                $tpl->data = $rows;
                $tpl->template = 'loadPhotos.tpl.php';
                $json['type'] = "success";
                $json['html'] = $tpl->render();
            else:
                $json['type'] = "error";
            endif;
            print json_encode($json);
            break;
        
        /* == Edit Image Title == */
        case "editGallery":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->data = Db::Go()->select(Items::gTable)->where("id", Filter::$id, "=")->first()->run();
            $tpl->template = 'editImageTitle.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Get Item Stats == */
        case "listingStats":
            $data = Stats::listingStats(Filter::$id);
            print json_encode($data);
            break;
        
        /* == Reset Item Stats == */
        case "resetListingStats":
            $data = Db::Go()->delete(Stats::sTable)->where("listing_id", Filter::$id, "=")->run();
            print json_encode($data);
            break;
        
        /* == Get Models == */
        case "getModels":
            if ($data = Db::Go()->select(Content::mdTable, array("id", "name"))->where("make_id", Filter::$id, "=")->run()):
                $json['type'] = "success";
                $json['data'] = $data;
            else:
                $json['type'] = "error";
            endif;
            print json_encode($json);
            break;
        
        /* == Approve Listing == */
        case "approveListing":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->row = Db::Go()->select(Items::lTable, array("id", "nice_title"))->where("id", Filter::$id, "=")->where("status", 1, "<")->first()->run();
            $tpl->template = 'approveListing.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Reject Listing == */
        case "rejectListing":
            $tpl = App::View(ADMINBASE . '/snippets/');
            $tpl->row = Db::Go()->select(Items::lTable, array("id", "nice_title"))->where("id", Filter::$id, "=")->where("status", 1, "<")->first()->run();
            $tpl->template = 'rejectListing.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Print Item == */
        case "print":
            if ($row = Db::Go()->select(Items::lTable, array("id", "thumb", "nice_title", "features", "gallery"))->where("id", Filter::$id, "=")->first()->run()):
                $title = Validator::sanitize($row->nice_title, "alpha");
                
                $tpl = App::View(ADMINBASE . '/snippets/');
                $tpl->row = $row;
                $tpl->data = App::Items()->listingPreview($tpl->row->id);
                
                $tpl->gallery = Utility::jSonToArray($tpl->row->gallery);
                $tpl->location = Utility::jSonToArray($tpl->data->location_name);
                
                $features = array();
                $tpl->features = null;
                $fdata = App::Items()->listingFeatures($tpl->row->features);
                if ($fdata) {
                    foreach ($fdata as $row) {
                        $features[] = $row->name;
                    }
                    $tpl->features = Utility::tableGrid($features, 2);
                }
                
                $tpl->core = App::Core();
                $tpl->template = 'Print_Pdf.tpl.php';
                
                require_once(BASEPATH . 'lib/mPdf/vendor/autoload.php');
                try {
                    $mpdf = new Mpdf(['mode' => 'utf-8']);
                    $mpdf->SetTitle($title);
                    $mpdf->WriteHTML($tpl->render());
                    $mpdf->Output($title . ".pdf", "D");
                } catch (MpdfException $e) {
                    Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                }
            
            endif;
            exit;
            break;
        
        /* == Print Invoice == */
        case "invoice":
            if ($row = App::Users()->userInvoice(Filter::$id, intval($_GET['uid']))) :
                $title = Validator::sanitize($row->nice_title, "alpha");
                
                $tpl = App::View(BASEPATH . 'mailer/' . App::Core()->lang . '/');
                $tpl->row = $row;
                
                $tpl->core = App::Core();
                $tpl->template = 'Print_Pdf.tpl.php';
                
                require_once(BASEPATH . 'lib/mPdf/vendor/autoload.php');
                try {
                    $mpdf = new Mpdf(['mode' => 'utf-8']);
                    $mpdf->SetTitle($title);
                    $mpdf->WriteHTML($tpl->render());
                    $mpdf->Output($title . ".pdf", "D");
                } catch (MpdfException $e) {
                    Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                }
            
            endif;
            exit;
            break;
        
        /* == Export User Payments == */
        case "userPaymentsCvs":
            header("Pragma: no-cache");
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=UserPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'Item', 'Amount', 'TAX/VAT', 'Coupon', 'Total', 'Processor', 'Currency', 'Created', 'IP'));
            
            $result = Stats::userPayments((int)Filter::$id);
            if ($result):
                $array = json_decode(json_encode($result), true);
                foreach ($array as $row) :
                    fputcsv($data, $row);
                endforeach;
            endif;
            break;
        
        /* == Remote Images == */
        case "getImages":
            $result = File::scanDirectory(UPLOADS . '/images', array("include" => array("jpg", "jpeg", "bmp", "png", "svg")), "name");
            $list = array();
            foreach ($result['files'] as $row) :
                $clean = preg_replace('/\\.[^.\\s]{3,4}$/', '', $row['name']);
                $item = array(
                    'url' => UPLOADURL . '/' . $row['url'],
                    'thumb' => UPLOADURL . '/images/thumbs/' . $row['name'],
                    'id' => strtolower($clean),
                    'name' => $clean,
                );
                $list[] = $item;
            endforeach;
            print json_encode($list);
            break;
    endswitch;
    
    /* == Instant Actions== */
    switch ($iAction) :
        /* == Sort Menus == */
        case "sortMenus":
            $jsonstring = $_POST['sorting'];
            $jsonDecoded = json_decode($jsonstring, true, 12);
            $result = Utility::parseJsonArray($jsonDecoded);
            
            $i = 0;
            $query = "UPDATE `" . Content::muTable . "` SET `sorting` = CASE ";
            $idlist = '';
            foreach ($result as $item):
                $i++;
                $query .= " WHEN id = " . $item['id'] . " THEN " . $i . " ";
                $idlist .= $item['id'] . ',';
            endforeach;
            $idlist = substr($idlist, 0, -1);
            $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
            Db::Go()->rawQuery($query)->run();
            break;
        
        /* == Sort Features == */
        case "sortFeature":
            $i = 0;
            $query = "UPDATE `" . Content::fTable . "` SET `sorting` = CASE ";
            $idlist = '';
            foreach ($_POST['sorting'] as $item):
                $i++;
                $query .= " WHEN id = " . $item . " THEN " . $i . " ";
                $idlist .= $item . ',';
            endforeach;
            $idlist = substr($idlist, 0, -1);
            $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
            Db::Go()->rawQuery($query)->run();
            break;
        
        /* == Sort Faq == */
        case "sortFaq":
            $i = 0;
            $query = "UPDATE `" . Content::faqTable . "` SET `sorting` = CASE ";
            $idlist = '';
            foreach ($_POST['sorting'] as $item):
                $i++;
                $query .= " WHEN id = " . $item . " THEN " . $i . " ";
                $idlist .= $item . ',';
            endforeach;
            $idlist = substr($idlist, 0, -1);
            $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
            Db::Go()->rawQuery($query)->run();
            break;
        
        /* == Sort Slider == */
        case "sortSlide":
            $i = 0;
            $query = "UPDATE `" . Content::slTable . "` SET `sorting` = CASE ";
            $idlist = '';
            foreach ($_POST['sorting'] as $item):
                $i++;
                $query .= " WHEN id = " . $item . " THEN " . $i . " ";
                $idlist .= $item . ',';
            endforeach;
            $idlist = substr($idlist, 0, -1);
            $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
            Db::Go()->rawQuery($query)->run();
            break;
        
        /* == Sort Gallery == */
        case "sortGallery":
            $i = 0;
            $query = "UPDATE `" . Items::gTable . "` SET `sorting` = CASE ";
            $idlist = '';
            foreach ($_POST['sorting'] as $item):
                $i++;
                $query .= " WHEN id = " . $item . " THEN " . $i . " ";
                $idlist .= $item . ',';
            endforeach;
            $idlist = substr($idlist, 0, -1);
            $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
            Db::Go()->rawQuery($query)->run();
            break;
        
        /* == Database Backup == */
        case "databaseBackup":
            dbTools::doBackup();
            break;
        
        /* == Listing Status == */
        case "listingStatus":
            if (Db::Go()->update(Items::lTable, array("status" => intval($_POST['value'] == 1) ? 0 : 1))->where("id", Filter::$id, "=")->run()):
                $json['type'] = "success";
                print json_encode($json);
            endif;
            break;
        
        /* == Listing Featured == */
        case "listingFeatured":
            if (Db::Go()->update(Items::lTable, array("featured" => intval($_POST['value'] == 1) ? 0 : 1))->where("id", Filter::$id, "=")->run()):
                $icon = intval($_POST['value'] == 0) ? "check positive" : "slash circle negative";
                $json['type'] = "success";
                $json['html'] = '<i id="icon_featured_' . Filter::$id . '" class="' . $icon . ' icon"></i>';
                print json_encode($json);
            endif;
            break;
        
        /* == Listing Sold == */
        case "listingSold":
            if (Db::Go()->update(Items::lTable, array("sold" => intval($_POST['value'] == 1) ? 0 : 1))->where("id", Filter::$id, "=")->run()):
                $icon = intval($_POST['value'] == 0) ? "check positive" : "slash circle negative";
                $json['type'] = "success";
                $json['html'] = '<i id="icon_sold_' . Filter::$id . '" class="' . $icon . ' icon"></i>';
                print json_encode($json);
            endif;
            break;
        
        /* == Clear Session Temp Queries == */
        case "session":
            App::Session()->remove('debug-queries');
            App::Session()->remove('debug-warnings');
            App::Session()->remove('debug-errors');
            print "ok";
            break;
    endswitch;