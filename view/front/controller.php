<?php
    /**
     * Controller
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: controller.php, v1.00 2022-09-05 10:12:05 gewa Exp $
     */
    
    use Mpdf\Mpdf;
    use Mpdf\MpdfException;
    
    const _WOJO = true;
    require_once("../../init.php");
    
    $pAction = Validator::post('action');
    $gAction = Validator::get('action');
    $iAction = Validator::post('iaction');
    $delete = Validator::post('delete');
    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
    
    /* == Post Actions == */
    switch ($pAction):
        /* == Admin Password Reset == */
        case "aResetPass":
            App::Admin()->passReset();
            break;
        
        /* == User Password Reset == */
        case "uResetPass":
            App::Front()->passReset();
            break;
        
        /* == Pass Reset == */
        case "password":
            App::Front()->passwordChange();
            break;
        
        /* == Admin Login == */
        case "adminLogin":
            App::Auth()->adminLogin($_POST['username'], $_POST['password']);
            break;
        
        /* == User Login == */
        case "userLogin":
            App::Auth()->userLogin($_POST['username'], $_POST['password']);
            break;
        
        /* == Registration == */
        case "register":
            App::Front()->Registration();
            break;
        
        /* == Update Profile == */
        case "profile":
            if (!App::Auth()->is_User())
                exit;
            App::Front()->updateProfile();
            break;
        
        /* == Newsletter == */
        case "processNewsletter":
            App::Content()->processNewsletter();
            break;
        /* == Contact Seller == */
        case "contactSeller":
            App::Front()->contactSeller();
            break;
        
        /* == Buy Membership == */
        case "membership":
            if (!App::Auth()->is_User())
                exit;
            App::Front()->buyMembership();
            break;
        
        /* == Add Review  == */
        case "addReview":
            if (!App::Auth()->is_User())
                exit;
            App::Content()->addReview();
            break;
        
        /* == Apply Coupon == */
        case "coupon":
            if (!App::Auth()->is_User())
                exit;
            App::Front()->getCoupon();
            break;
        
        /* == Activate Coupon == */
        case "activateCoupon":
            if (!App::Auth()->is_User())
                exit;
            App::Front()->activateCoupon();
            break;
        
        /* == Select Gateway == */
        case "selectGateway":
            if (!App::Auth()->is_User())
                exit;
            App::Front()->selectGateway();
            break;
        
        /* == New Location == */
        case "addLocation":
            if (!App::Auth()->is_User())
                exit;
            App::Content()->processLocation(true);
            break;
        
        /* == Add Listing  == */
        case "processItem":
            if (!App::Auth()->is_User() and App::Auth()->membership_id)
                exit;
            App::Front()->addListing();
            break;
        
        /* == Process Images == */
        case "processImages":
            if (!App::Auth()->is_User())
                exit;
            $num_files = count($_FILES['images']['tmp_name']);
            $filedir = UPLOADS . '/listings/pics___temp___' . Filter::$id;
            $maxSize = 8388608;
            $exts = ['png', 'jpg', 'jpeg'];
            $json = [];
            File::makeDirectory($filedir . '/thumbs');
            
            for ($x = 0; $x < $num_files; $x++):
                $image = $_FILES['images']['name'][$x];
                $newName = "IMG_" . Utility::randomString(12);
                $ext = substr($image, strrpos($image, '.') + 1);
                $name = $newName . "." . strtolower($ext);
                $fullname = $filedir . '/' . $name;
                
                if ($_FILES["images"]["size"][$x] > $maxSize):
                    $json['type'] = "error";
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
                    print json_encode($json);
                    exit;
                endif;
                
                if (!in_array($ext, $exts)):
                    $json['type'] = "error";
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->FU_ERROR8 . "jpg, png, jpeg"; //invalid extension
                    print json_encode($json);
                    exit;
                endif;
                
                if (!getimagesize($_FILES['images']["tmp_name"][$x])):
                    $json['type'] = "error";
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->FU_ERROR7; //invalid image
                    print json_encode($json);
                    exit;
                endif;
                
                if (!move_uploaded_file($_FILES['images']['tmp_name'][$x], $fullname)):
                    $json['type'] = "error";
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->FU_ERROR13; //cant move  image
                    print json_encode($json);
                    exit;
                endif;
                
                try {
                    $img = new Image($filedir . '/' . $name);
                    $img->crop(Items::THUMBW, Items::THUMBH)->save($filedir . '/thumbs/' . $name);
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'listing_id' => Filter::$id,
                    'photo' => $name,
                    'title' => "-/-",
                );
                $last_id = Db::Go()->insert(Items::gTable, $data)->run();
                
                $json['type'] = "success";
                $json['html'][$x] = '
                <div class="columns" id="item_' . $last_id . '" data-id="' . $last_id . '">
                  <div class="wojo attached simple fitted segment center aligned"><img src="' . UPLOADURL . '/listings/pics___temp___' . Filter::$id . '/thumbs/' . $name . '" alt="" class="wojo rounded grab image">
                    <div class="wojo buttons middle attached">
                      <a data-set=\'{"option":[{"action":"editGallery","id": ' . $last_id . '}], "label":"' . Lang::$word->EDIT . '", "url":"/controller.php", "parent":"#title_' . $last_id . '", "complete":"replace", "modalclass":"normal"}\' class="wojo mini icon positive button action"><i class="icon pencil fill"></i></a>
                      <a data-set=\'{"option":[{"delete": "deleteGallery","title": "-/-","id": ' . $last_id . '}],"action":"delete","parent":"#item_' . $last_id . '"}\' class="wojo mini icon negative button data"><i class="icon trash"></i></a>
                    </div>
                  </div>
				  <p class="wojo small demi text" id="title_' . $last_id . '">-/-</p>
                </div>';
            endfor;
            print json_encode($json);
            break;
        
        /* == Update Gallery Title == */
        case "editGallery":
            if (!App::Auth()->is_User())
                exit;
            if (Db::Go()->update(Items::gTable, array("title" => $title))->where("id", Filter::$id, "=")->run()):
                $json['type'] = "success";
                $json['title'] = $title;
                $json['html'] = $title;
                print json_encode($json);
            endif;
            break;
    endswitch;
    
    /* == Get Actions == */
    switch ($gAction):
        /* == Get Compare == */
        case "compare":
            $columns = array("id", "idx", "thumb", "slug", "nice_title");
            if ($row = Db::Go()->select(Items::lTable, $columns)->where("id", Filter::$id, "=")->where("status", 1, "=")->first()->run()):
                $tpl = App::View(THEMEBASE . '/snippets/');
                $tpl->template = 'loadCompare.tpl.php';
                $tpl->row = $row;
                
                App::Session()->setKey("CDP_compare", Filter::$id, json_encode($row));
                
                $json['type'] = "success";
                $json['message'] = "added";
                $json['html'] = $tpl->render();
            else:
                $json['type'] = "error";
            endif;
            
            echo json_encode($json);
            break;
        
        /* == Remove Compare == */
        case "removeCompare":
            App::Session()->removeKey('CDP_compare', Filter::$id);
            
            $json['type'] = "success";
            $json['message'] = "removed";
            $json['html'] = '<div class="columns blank"><div class="blankCompare"></div></div>';
            echo json_encode($json);
            break;
        
        /* == Membership Invoice == */
        case "invoice":
            if (!App::Auth()->is_User())
                exit;
            
            if (empty($_GET['invoice_id'])) {
                Url::redirect(Url::currentUrl() . "?msg=" . urlencode(Lang::$word->FRONT_ERROR01));
                exit;
            }
            $id = Validator::sanitize($_GET['invoice_id'], "alphanumeric");
            
            if ($row = App::Users()->userInvoice(Utility::decode($id), App::Auth()->uid)):
                $tpl = App::View(THEMEBASE . '/snippets/');
                $tpl->row = $row;
                $tpl->user = Auth::$userdata;
                $tpl->core = App::Core();
                $tpl->template = 'invoice.tpl.php';
                $title = Validator::sanitize($row->title, "alphanumeric");
                
                require_once(BASEPATH . 'lib/mPdf/vendor/autoload.php');
                try {
                    $mpdf = new Mpdf(['mode' => 'utf-8']);
                    $mpdf->SetTitle($title);
                    $mpdf->WriteHTML($tpl->render());
                    $mpdf->Output($title . ".pdf", "D");
                } catch (MpdfException $e) {
                    Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                }
            
            else:
                Url::redirect(Url::currentUrl() . "?msg=" . urlencode(Lang::$word->FRONT_ERROR01));
            endif;
            exit;
            break;
        
        /* == Edit Image Title == */
        case "editGallery":
            if (!App::Auth()->is_User())
                exit;
            $tpl = App::View(THEMEBASE . '/snippets/');
            $tpl->data = Db::Go()->select(Items::gTable)->where("id", Filter::$id, "=")->first()->run();
            $tpl->template = 'editImageTitle.tpl.php';
            echo $tpl->render();
            break;
        
        /* == Add New Location == */
        case "addLocation":
            if (!App::Auth()->is_User())
                exit;
            $tpl = App::View(THEMEBASE . '/snippets/');
            $tpl->template = 'newLocation.tpl.php';
            echo $tpl->render();
            break;
    endswitch;
    
    /* == iAction == */
    switch ($iAction):
        /* == Get Make List == */
        case "makeList":
            $html = "";
            if ($mids = Db::Go()->select(Items::lTable, array("COALESCE(GROUP_CONCAT(DISTINCT model_id), 0) as ids"))->where("make_id", Filter::$id, "=")->first()->run()) :
                $result = Db::Go()->rawQuery("SELECT id, name FROM `" . Content::mdTable . "` WHERE id IN(" . $mids->ids . ")")->run();
                $html .= "<option value=\"\">-- " . Lang::$word->LST_MODEL . " --</option>\n";
                foreach ($result as $row):
                    $html .= "<option value=\"" . $row->id . "\">" . $row->name . "</option>\n";
                endforeach;
            else:
                $html .= "<option value=\"\">-- " . Lang::$word->MAKE_NAME_R . " --</option>\n";
            endif;
            
            $json['type'] = "success";
            $json['message'] = $html;
            echo json_encode($json);
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
        
        /* == Get Popular Categories List == */
        case "popCategory":
            $html = "";
            if ($result = Db::Go()->select(Items::lTable, array("idx", "nice_title", "slug", "thumb", "year", "price"))
                ->where("status", 1, "=")
                ->where("featured", 1, "=")
                ->where("category", Filter::$id, "=")
                ->orderBy("created", "DESC")
                ->limit(App::Core()->featured)
                ->run())
                :
                foreach ($result as $row):
                    $html .= '
                    <div class="wojo photo simple attached card">
                      <a href="' . Url::url("/listing/" . $row->idx, $row->slug) . '" class="wojo top rounded full zoom image">
                      <img src="' . UPLOADURL . '/listings/thumbs/' . $row->thumb . '" data-lazy="' . UPLOADURL . '/listings/thumbs/' . $row->thumb . '" alt=""></a>
                      <div class="footer wojo secondary bg">
                        <h6 class="wojo white text truncate">
                          <a href="' . Url::url("/listing/" . $row->idx, $row->slug) . '" class="white">' . $row->nice_title . '</a>
                        </h6>
                        <div class="row small horizontal gutters align middle">
                          <div class="columns"><span class="wojo small primary label">' . $row->year . '</span></div>
                          <div class="columns auto"><span class="wojo primary bold text">' . Utility::formatMoney($row->price) . '</span></div>
                        </div>
                      </div>
                    </div>';
                endforeach;
                
                $json['type'] = "success";
                $json['html'] = $html;
            else:
                $json['type'] = "error";
            endif;
            echo json_encode($json);
            break;
        
        /* == Mark Sold == */
        case "sold":
            if (!App::Auth()->is_User())
                exit;
            
            if (Db::Go()->update(Items::lTable, array("sold" => 1))->where("id", Filter::$id, "=")->run()):
                $json['type'] = "success";
                $json['html'] = '<a class="wojo small simple passive button"><i class="icon check"></i>' . Lang::$word->SOLD . '</a>';
                print json_encode($json);
            endif;
            break;
        
        /* == Sort Gallery == */
        case "sortGallery":
            if (!App::Auth()->is_User())
                exit;
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
				  WHERE id IN (" . $idlist . ") 
				  AND `user_id` = ?";
            Db::Go()->rawQuery($query, array(App::Auth()->uid))->run();
            break;
        
        /* == Clear Session == */
        case "session":
            App::Session()->remove('debug-queries');
            App::Session()->remove('debug-warnings');
            App::Session()->remove('debug-errors');
            print "ok";
            break;
    endswitch;
    
    /* == Delete Actions == */
    switch ($delete):
        /* == Delete Listing == */
        case "deleteListing":
            if (!App::Auth()->is_User())
                exit;
            
            $row = Db::Go()->select(Items::gTable, array("thumb", "file"))->where("id", Filter::$id, "=")->where("user_id", App::Auth()->uid, "=")->first()->run();
            File::deleteFile(UPLOADS . "/listings/" . $row->thumb);
            File::deleteFile(UPLOADS . "/listings/thumbs/" . $row->thumb);
            File::deleteFile(UPLOADS . "/listings/files/" . $row->file);
            
            $res = Db::Go()->delete(Items::lTable)->where("id", Filter::$id, "=")->run();
            Db::Go()->delete(Items::liTable)->where("listing_id", Filter::$id, "=")->run();
            Db::Go()->delete(Items::gTable)->where("listing_id", Filter::$id, "=")->run();
            
            $pics = UPLOADS . "/listings/pics" . Filter::$id;
            File::deleteRecursive($pics, true);
            
            Items::doCalc();
            
            $count = Db::Go()->count(Items::lTable)->where("user_id", App::Auth()->uid, "=")->where("status", 1, "=")->run();
            Db::Go()->update(Users::mTable, array("listings" => $count))->where("id", App::Auth()->uid, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->LST_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Gallery Image == */
        case "deleteGallery":
            if ($row = Db::Go()->select(Items::gTable, array("listing_id", "photo"))->where("id", Filter::$id, "=")->first()->run()):
                File::deleteFile(UPLOADS . '/listings/pics___temp___' . $row->listing_id . '/' . $row->photo);
                File::deleteFile(UPLOADS . '/listings/pics___temp___' . $row->listing_id . '/thumbs/' . $row->photo);
                
                Db::Go()->delete(Items::gTable)->where("id", Filter::$id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->GAL_DELOK);
            print json_encode($json);
            break;
    endswitch;