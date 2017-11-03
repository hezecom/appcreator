<?php
	/*
	* =======================================================================
	* FILE NAME:        people.router.php
	* DATE CREATED:  	25-10-2017
	* FOR TABLE:  		people
	* AUTHOR:			Hezecom Technology Solutions LTD.
	* CONTACT:			http://hezecom.com <info@hezecom.com>
	* =======================================================================
	*/
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Model\HTSPrivilegedUser;
use App\Model\People;
	
class PeopleController extends AdminBaseController
{
	private function routeURL($path)
    {
        return $this->htsUrl.'/admin/people/'.$path;
    }

    private function model()
    {
        return new People();
    }
	
	/*View Controller*/
   public function View(Request $request,  Response $response, $args)
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-view');
            /*Table header*/
            $primary_key = 'id';/*this will be hidden*/
             $actionButton = '<span class="right">'.$this->lang['HTS_ACTIONS'].'</span>';
             $fieldLabel = array($primary_key, $this->lang['PEOP_NAME'],$this->lang['PEOP_GENDER'],$this->lang['PEOP_DATE_REGISTERED'],$this->lang['PEOP_PICTURE'],$actionButton);
            
            /*send data to template*/
            $htsvars = [
                'page' => [
                    'title' => 'People- ' . H_TITLE,
                    'ptitle' => 'People',
                    'viewproURL' => $this->routeURL('viewpro'),
                    'lastColumn' => count($fieldLabel)-1,
                    'addURL' => $this->routeURL('add'),
                    'exportURL' => $this->routeURL('export'),
                    'detailsURL' => 'details',
                    'deleteURL' => $this->routeURL('delete'),
                ],
                'fieldLabels' => $fieldLabel
            ];
            return $this->view->render($response, 'admin/views/people/View.html', $htsvars);
    }


    /*Datatable Procesing*/
    public function ViewPro($request, $response, $args)
    {
        /*table data to display*/
        $primary_key='id';
        $columns = array($primary_key, 'name','gender','date_registered','picture');
        /*get root link*/
        $result= $this->model()->SelectTableData($columns,'update');
        echo json_encode($result);
    }


    /*export*/
    public function Export()
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-export');
        $printer=get('p');
        $result =$this->model()->SelectAll(10000);
        include($this->htsPath.'templates/admin/views/people/Export.phtml');
        if($printer=='csv') {
            \App\Lib\HDB::hus()->ExportTable('people');
        }elseif($printer=='json') {
            Json_Export($result, 'people');
        }
    }


    /*Details*/
    public function Details($request, $response, $args)
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-details');
        $htsvars = [
            'page' => [
                'title' => 'People Detail'. H_TITLE,
                'addURL' => $this->routeURL('add'),
                'viewURL' => $this->routeURL('view'),
                'updateURL' => $this->routeURL('update/'.$args['id']),
                'deleteURL' => $this->routeURL('delete')
            ],
            'row' => $this->model()->SelectOne($args['id']),
            'lists' => $this->model()->SelectAll(10),
            
        ];
        return $this->view->render($response, 'admin/views/people/Details.html', $htsvars);
    }


    /*add*/
    public function Add($request, $response, $args)
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-add');
        $htsvars = [
            'page' => [
                'title' => 'Add People - '.H_TITLE,
                'proURL' => $this->routeURL('addpro'),
                'viewURL' => $this->routeURL('view'),
            ]
        ];
        return $this->view->render($response, 'admin/views/people/Add.html', $htsvars);
    }

    /*Add Process*/
    public function AddPro($request, $response, $args)
    {
         HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-add');
        if($_POST){
        /*form validation*/
        if (post('name')==''){
        field_error($this->lang['PEOP_NAME']);
        }
        elseif (post('gender')==''){
        field_error($this->lang['PEOP_GENDER']);
        }
        elseif (post('date_registered')==''){
        field_error($this->lang['PEOP_DATE_REGISTERED']);
        }
        elseif (post('others')==''){
        field_error($this->lang['PEOP_OTHERS']);
        }
        else{
        $this->model()->Insert(post('name'),post('gender'),post('date_registered'),post('others'));
        json_success($this->lang['LANG_ADD_SUCCESS']);
        }
        }
	}
	


    /*update*/
    public function Update($request, $response, $args)
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-update');
        $htsvars = [
            'page' => [
                'title' => 'Update People - '.H_TITLE,
                'proURL' => $this->routeURL('addpro'),
                'addURL' => $this->routeURL('add'),
                'viewURL' => $this->routeURL('view'),
                'deleteURL' => $this->routeURL('delete'),
                'updateproURL' => $this->routeURL('updatepro'),
                
            ],
            'row' => $this->model()->SelectOne($args['id']),
            
        ];
        return $this->view->render($response, 'admin/views/people/Update.html', $htsvars);
    }
   
    /*Update Process*/
    public function UpdatePro($request, $response, $args)
    {
        HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-update');
        if($_POST){
	    /*form validation*/
        if (post('id')==''){
        field_error($this->lang['PEOP_OTHERS']);
        }
        elseif (post('name')==''){
        field_error($this->lang['PEOP_NAME']);
        }
        elseif (post('gender')==''){
        field_error($this->lang['PEOP_GENDER']);
        }
        elseif (post('date_registered')==''){
        field_error($this->lang['PEOP_DATE_REGISTERED']);
        }
        elseif (post('others')==''){
        field_error($this->lang['PEOP_OTHERS']);
        }
        else{
        $this->model()->Update(post('name'),post('gender'),post('date_registered'),post('others'),post('id'));
        json_success($this->lang['LANG_UPDATE_SUCCESS']);
        }
        }
    }
    
	/*Delete*/
	public function Delete($request, $response, $args)
	 {
	    HTSPrivilegedUser::HTSuser()->HTSPrivilege('people-delete');
        $dfile = $this->model()->SelectOne(get('id'));
        delete_files(UPLOAD_PATH.$dfile->picture);
        delete_files(THUMB_PATH.$dfile->picture);
        $rows = $this->model()->Delete(get('id'));
        return $response->withStatus(302)->withHeader('Location', $this->routeURL('view'));
     }
}

	