<header class="header">
<?php
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
?>
      <div class="top-header-section">
            <div class="container-fluid">
                  <div class="row align-items-center">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                              <div class="logo my-3 my-sm-0">
                                    <a href="{{ route('dashboard') }}">
                                          <img src="{{ asset('assets/img/hram_logo_new_1.png') }}" alt="logo image" class="img-fluid" width="100">
                                    </a>
                              </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                              <p class="header_top_content">
                              <i>Build more together.</i>
                              </p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-2 text-right">
                              <div class="user-block d-none d-lg-block">
                                    <div class="row align-items-center">
                                          <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="user-notification-block align-right d-inline-block">
                                                </div>

                                                <div class="user-notification-block align-right d-inline-block">
                                                      <ul class="list-inline m-0">
                                                      </ul>
                                                </div>


                                                <div class="user-info align-right dropdown d-inline-block header-dropdown">
                                                      <a href="javascript:void(0)" data-toggle="dropdown" class=" menu-style dropdown-toggle">
                                                            <div class="user-avatar d-inline-block" id="profile_pic_data">
                                                            <?php
                                                            use Illuminate\Support\Facades\DB;
                                                            use Illuminate\Support\Facades\Auth;
                                                           
                                                            $userInfo = Auth::user();
                                                            $user_id = Auth::user()->id;
                                                            $image_profile = DB::select("select profile_pic from emp_accounts where user_id=".$user_id);
                                                            $profileImage = "";
                                                            if(isset($image_profile[0]) && $image_profile[0]->profile_pic!=""){
                                                                  $profileImage = $image_profile[0]->profile_pic;
                                                                  $filepath = Auth::user()->employee_code;
                                                            ?>                                    
                                                             <img src="{{ asset('doc_images'.$filepath.'/'.$profileImage) }}" alt="user avatar" class="rounded-circle img-fluid" width="55">
                                                                  
                                                                  <?php }else{?>
                                                                        <img src="{{ asset('assets/img/profiles/logo.png') }}" alt="user avatar" class="rounded-circle img-fluid" width="55">
                                                                        <?php }?>
                                                                        <!-- <div class="jdghgj">
                                                                              <form>
                                                                              <input type="file">
                                                                              <i class="fa fa-camera" aria-hidden="true"></i>
                                                                  </form>
                                                                              
                                                                        </div> -->
                                                                        
                                                                        <div class="input--file">
                                                                       
                                                                        <!-- <form id="prifile_pic_form" enctype="multipart/form-data"> -->
                                                                        @csrf
                                                                        <span>
                                                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                        <circle cx="12" cy="12" r="3.2"/>
                                                                        <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                                                        <path d="M0 0h24v24h-24z" fill="none"/>
                                                                        </svg> -->
                                                                        <!-- <i class="fa fa-camera" aria-hidden="true"></i>
                                                                        </span>
                                                                        <input name="profile_picture" type="file" id="profile_picture"/>
                                                                        </form> -->
                                                                        </div>
                                                            </div>
                                                      </a>
                                                      
                                                      <div class="dropdown-menu notification-dropdown-menu shadow-lg border-0 p-3 m-0 dropdown-menu-right">
                                                     
                                                      <a class="dropdown-item p-2" href="{{ route('employeeview') }}">
                                                                  <span class="media align-items-center">
                                                                        <span class="lnr lnr-user mr-3"></span>
                                                                        <span class="media-body text-truncate">
                                                                              <span class="text-truncate">{{$userInfo->name}}</span>
                                                                        </span>
                                                                  </span>
                                                            </a>
                                                            <div class="sss">
                                                            <a class="dropdown-item p-2" href="profile-settings.html">
                                                                  <span class="media align-items-center">
                                                                        <span class="lnr lnr-cog mr-3"></span>
                                                                        <span class="media-body text-truncate">
                                                                              <span class="text-truncate"><a class="text-truncate" href="{{route('changepassword')}}">Change Password</a></span>
                                                                        </span>
                                                                  </span>
                                                            </a>
                                                            </div>
                                                            <div class="sss">
                                                            <a class="dropdown-item p-2" href="login.html">
                                                                  <span class="media align-items-center">
                                                                        <span class="lnr lnr-power-switch mr-3"></span>
                                                                        <span class="media-body text-truncate">
                                                                              <span class="text-truncate"><a class="text-truncate" href="{{route('logout')}}">Logout</a></span>
                                                                        </span>
                                                                  </span>
                                                            </a>
                                                            </div>
                                                      </div>

                                                </div>

                                          </div>
                                    </div>
                              </div>
                              <div class="d-block d-lg-none">
                                    <a href="javascript:void(0)">
                                          <span class="lnr lnr-user d-block display-5 text-white" id="open_navSidebar"></span>
                                    </a>

                                    <div class="offcanvas-menu" id="offcanvas_menu">
                                          <span class="lnr lnr-cross float-left display-6 position-absolute t-1 l-1 text-white" id="close_navSidebar"></span>
                                          <div class="user-info align-center bg-theme text-center">
                                                <a href="javascript:void(0)" class="d-block menu-style text-white">
                                                      <div class="user-avatar d-inline-block mr-3">
                                                            <img src="{{ asset('assets/img/profiles/logo.png') }}" alt="user avatar" class="rounded-circle" width="50">
                                                      </div>
                                                </a>
                                          </div>
                                          <div class="user-notification-block align-center">
                                          </div>
                                          <hr>
                                          <div class="user-menu-items px-3 m-0">
                                          <?php   
                                         
                                         
                                         $roleType = Auth::user()->role;
                                         $selectMenu = DB::select("select * from menus where status=1 or status=2");
                                         $selectRole = DB::select("select * from role where department='".$roleType."'");
                                         $accessRole = explode(',', $selectRole[0]->access);      
                                         $finalMenuData = array_filter($selectMenu, function ($data) use($accessRole) {
                                         return (in_array($data->id,$accessRole));
                                         });

                                         $allRoutes = array_column($finalMenuData, 'routes_name');
                                         if($roleType!=5){
                                         // echo Route::currentRouteName();
                                         // echo "<pre>";
                                         // prinr_r($allRoutes);
                                         if(!in_array(Route::currentRouteName(),$allRoutes)){
                                               ?><script> window.location.href = "{{ route('dashboard')}}";</script><?php
                                         }

                                         }
                                         
                                         $finalMenuDatas = array_filter($finalMenuData, function ($data) use($accessRole) {
                                         return (in_array($data->id,$accessRole) && ($data->status!=2));
                                         });
                                        
  
?>                                           @foreach($finalMenuDatas as $menuData)
                                               <a class="px-0 pb-2 pt-0" href="{{route($menuData->routes_name)}}">
                                                     <span class="media align-items-center">
                                                           <span class="lnr {{$menuData->class_name}} mr-3"></span>
                                                           <span class="media-body text-truncate text-left">
                                                                 <span class="text-truncate text-left">{{$menuData->name}}</span>
                                                           </span>
                                                     </span>
                                               </a>
                                               @endforeach
                                                <a class="px-0 pb-2 pt-0" href="{{route('logout')}}">
                                                      <span class="media align-items-center">
                                                            <span class="lnr lnr-power-switch mr-3"></span>
                                                            <span class="media-body text-truncate text-left">
                                                                  <span class="text-truncate text-left">Logout</span>
                                                            </span>
                                                      </span>
                                                </a>
                                          </div>
                                    </div>

                              </div>
                        </div>
                  </div>
            </div>
      </div>

</header>

<script>
 $(document).ready(function(){
 $(document).on('change', '#profile_picture', function(){
  var name = document.getElementById("profile_picture").files[0].name;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
  {
   alert("Invalid Image File");
  }
  var oFReader = new FileReader();
  oFReader.readAsDataURL(document.getElementById("profile_picture").files[0]);
  var f = document.getElementById("profile_picture").files[0];
  var fsize = f.size||f.fileSize;
  if(fsize > 20000000)
  {
   alert("Image File Size is very big");
  }
  else
  {
   form_data.append("file", document.getElementById('profile_picture').files[0]);

   form_data.append('_token', '{{ csrf_token() }}');
   $.ajax({
    url:"{{ route('employeepicupload') }}",
    method:"POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend:function(){
     $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
    },   
    success:function(data)
    {
      var data = JSON.parse(data); 
     $('#profile_pic_data').html("<img src='"+data.image_path+"' alt='user avatar' class='rounded-circle img-fluid' width='55'>");
      
    }
   });
  }
 });
});
</script>

<div class="page-wrapper">
      <div class="container-fluid">
            <div class="row">