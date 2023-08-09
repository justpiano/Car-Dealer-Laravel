<?php
    /**
     * Controller
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: controller.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    const _WOJO = true;
    require_once("../../init.php");
    
    if (!App::Auth()->is_Admin())
        exit;
    
    $delete = Validator::post('delete');
    $trash = Validator::post('trash');
    $action = Validator::post('action');
    $restore = Validator::post('restore');
    $archive = Validator::post('archive');
    $unarchive = Validator::post('unarchive');
    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
    
    /* == Trash Actions == */
    switch ($trash):
        /* == Trash Menu == */
        case "trashMenu":
            if ($row = Db::Go()->select(Content::muTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "menu", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::muTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->MENU_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Page == */
        case "trashPage":
            if ($row = Db::Go()->select(Content::pgTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "page", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::pgTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->PAG_TRASH_OK);
            print json_encode($json);
            break;
        
        /* == Trash Coupon == */
        case "trashCoupon":
            if ($row = Db::Go()->select(Content::dcTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "coupon", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::dcTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->DC_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Faq == */
        case "trashFaq":
            if ($row = Db::Go()->select(Content::faqTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "faq", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::faqTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->FAQ_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Slide == */
        case "trashSlide":
            if ($row = Db::Go()->select(Content::slTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "slide", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::slTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->SLD_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Advert == */
        case "trashAdvert":
            if ($row = Db::Go()->select(Content::nwaTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "advert", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::nwaTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->NWA_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Package == */
        case "trashPackage":
            if ($row = Db::Go()->select(Content::msTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "membership", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Content::msTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->MSM_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash Location == */
        case "trashLocation":
            if ($row = Db::Go()->select(Content::lcTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "location", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run()->run();
                Db::Go()->delete(Content::lcTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->LOC_TRASHED_OK);
            print json_encode($json);
            break;
        
        /* == Trash User == */
        case "trashUser":
            if ($row = Db::Go()->select(Users::mTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "user", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Users::mTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->ACC_TRASH_OK);
            print json_encode($json);
            break;
        
        /* == Trash Staff == */
        case "trashStaff":
            if ($row = Db::Go()->select(Users::aTable)->where("id", Filter::$id, "=")->first()->run()):
                $data = array('type' => "staff", 'dataset' => json_encode($row));
                Db::Go()->insert(Core::txTable, $data)->run();
                Db::Go()->delete(Users::aTable)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->ACC_TRASH_OK);
            print json_encode($json);
            break;
    endswitch;
    
    /* == Delete Actions == */
    switch ($delete):
        /* == Delete Listing == */
        case "deleteListing":
            $row = Db::Go()->select(Items::lTable, array("thumb", "file"))->where("id", Filter::$id, "=")->first()->run();
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
        
        /* == Delete Model == */
        case "deleteModel":
            Db::Go()->delete(Content::mdTable)->where("id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->MODL_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Make == */
        case "deleteMake":
            Db::Go()->delete(Content::mkTable)->where("id", Filter::$id, "=")->run();
            Db::Go()->delete(Content::mdTable)->where("make_id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->MAKE_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Category == */
        case "deleteCategory":
            Db::Go()->delete(Content::ctTable)->where("id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->CAT_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Feature == */
        case "deleteFeature":
            Db::Go()->delete(Content::fTable)->where("id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->FEAT_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Condition == */
        case "deleteCondition":
            Db::Go()->delete(Content::cdTable)->where("id", Filter::$id, "=")->run();
            $data['cond_list_alt'] = Db::Go()->select(Content::cdTable)->orderBy("name", "ASC")->run('json');
            Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->TRNS_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Fuel == */
        case "deleteFuel":
            Db::Go()->delete(Content::fuTable)->where("id", Filter::$id, "=")->run();
            $data['fuel_list'] = Db::Go()->select(Content::fuTable)->orderBy("name", "ASC")->run('json');
            Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->FUEL_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Transmission == */
        case "deleteTransmission":
            Db::Go()->delete(Content::trTable)->where("id", Filter::$id, "=")->run();
            $data['trans_list'] = Db::Go()->select(Content::trTable)->orderBy("name", "ASC")->run('json');
            Db::Go()->update(Core::sTable, $data)->where("id", 1, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->TRNS_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Review == */
        case "deleteReview":
            Db::Go()->delete(Content::rwTable)->where("id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->SRW_DELOK);
            print json_encode($json);
            break;
        
        /* == Delete Database == */
        case "deleteBackup":
            File::deleteFile(UPLOADS . '/backups/' . $title);
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->DBM_DEL_OK);
            print json_encode($json);
            break;
        
        /* == Delete Gallery Image == */
        case "deleteGallery":
            if ($row = Db::Go()->select(Items::gTable, array("listing_id", "photo"))->where("id", Filter::$id, "=")->first()->run()):
                File::deleteFile(UPLOADS . '/listings/pics' . $row->listing_id . '/' . $row->photo);
                File::deleteFile(UPLOADS . '/listings/pics' . $row->listing_id . '/thumbs/' . $row->photo);
                
                Db::Go()->delete(Items::gTable)->where("id", Filter::$id, "=")->run();
                $json['type'] = "success";
            endif;
            
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->GAL_DELOK);
            print json_encode($json);
            break;
            
        /* == Delete Menu == */
        case "deleteMenu":
            Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
            
            $json['type'] = "success";
            $json['title'] = Lang::$word->SUCCESS;
            $json['message'] = str_replace("[NAME]", $title, Lang::$word->TRS_DELGOOD_OK);
            print json_encode($json);
            break;
            
        /* == Delete Trash == */
        case "trashAll":
            Db::Go()->truncate(Core::txTable)->run();
            Message::msgReply(true, 'success', Lang::$word->TRS_TRS_OK);
            break;
    endswitch;
    
    /* == Archive Actions == */
    switch ($archive):
    endswitch;
    
    /* == Unarchive Actions == */
    switch ($unarchive):
    endswitch;
    
    /* == Restore Actions == */
    switch ($restore):
        /* == Restore Menu == */
        case "restoreMenu":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::muTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Page == */
        case "restorePage":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::pgTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Coupon == */
        case "restoreCoupon":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::dcTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Faq == */
        case "restoreFaq":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::faqTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Slider == */
        case "restoreSlide":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::slTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Advert == */
        case "restoreAdvert":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::nwaTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Membership == */
        case "restoreMembership":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::msTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Showrooms == */
        case "restoreLocation":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::lcTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Members == */
        case "restoreUser":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Users::mTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Staff == */
        case "restoreStaff":
            if ($result = Db::Go()->select(Core::txTable, array('dataset'))->where("id", Filter::$id, "=")->first()->run()):
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Users::aTable);
                Db::Go()->delete(Core::txTable)->where("id", Filter::$id, "=")->run();
                
                Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->RESFRTR));
            endif;
            break;
        
        /* == Restore Database == */
        case "restoreBackup":
            dbTools::doRestore($title);
            break;
    endswitch;
    
    /* == Actions == */
    switch ($action):
        /* == Process Menu == */
        case "processMenu":
            App::Content()->processMenu();
            break;
        
        /* == Process Page == */
        case "processPage":
            App::Content()->processPage();
            break;
        
        /* == Process Coupon == */
        case "processCoupon":
            App::Content()->processCoupon();
            break;
        
        /* == Process Faq == */
        case "processFaq":
            App::Content()->processFaq();
            break;
        
        /* == Process Slide == */
        case "processSlide":
            App::Content()->processSlide();
            break;
        
        /* == Process Advert == */
        case "processAdvert":
            App::Content()->processAdvert();
            break;
        
        /* == Process Email Template == */
        case "processEtemplate":
            App::Content()->processEtemplate();
            break;
        
        /* == Process Mailer == */
        case "processEmail":
            App::Content()->processEmail();
            break;
        
        /* == Process Country == */
        case "processCountry":
            App::Content()->processCountry();
            break;
        
        /* == Process Gateway == */
        case "processGateway":
            App::Admin()->processGateway();
            break;
        
        /* == Process Package == */
        case "processPackage":
            App::Content()->processPackage();
            break;
        
        /* == Process Listing == */
        case "processItem":
            App::Items()->processItem();
            break;
        
        /* == Process Location == */
        case "processLocation":
            App::Content()->processLocation();
            break;
        
        /* == Process Category == */
        case "processCategory":
            App::Content()->processCategory();
            break;
        
        /* == Process Member == */
        case "processMember":
            App::Users()->processMember();
            break;
        
        /* == Process Staff == */
        case "processStaff":
            App::Users()->processStaff();
            break;
        
        /* == Quick Message == */
        case "quickMessage":
            App::Users()->quickMessage();
            break;
        
        /* == Update Account == */
        case "updateAccount":
            App::Admin()->updateAccount();
            break;
        
        /* == Update Password == */
        case "updatePassword":
            App::Admin()->updateAdminPassword();
            break;
        
        /* == Delete Cart == */
        case "processMCart":
            Stats::emptyCart();
            break;
        
        /* == Process Theme Colors == */
        case "processColors":
            App::Core()->processColors();
            break;
        
        /* == Generate SiteMap == */
        case "processMap":
            Content::writeSiteMap();
            break;
        
        /* == Process Configuration == */
        case "processConfig":
            App::Core()->processConfig();
            break;
    endswitch;