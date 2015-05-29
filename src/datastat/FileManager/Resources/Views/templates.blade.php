<script class="tpl_filemanager" type="text/x-jsrender">
    <div>
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="file-manager">
                            <h5>Mape</h5>
                            <ul class="folder-list js-folder_list" style="padding: 0">
                            </ul>
                            <div class="btn btn-sm btn-primary js-create_root_folder">Ustvari novo vrhnjo mapo</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="js-select_folder">
                                    <h2>Prosim izberi mapo na levi</h2>
                                </div>
                                <div class="js-files_container hidden">

                                    <div class="btn btn-success js-rename_folder">
                                        Preimenuj mapo
                                    </div>

                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Dodaj datoteke</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                        <input
                                            class="js-fileupload"
                                            data-type="video"
                                            type="file"
                                            multiple="multiple"
                                            name="files[]"
                                            />
                                    </span>

                                    <div id="progress" class="progress" style="display: none;">
                                        <div class="progress-bar progress-bar-success"></div>
                                    </div>

                                    <hr />

                                    <div class="js-folder_contents">

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script class="tpl_filemanager_folder" type="text/x-jsrender">
    <li data-id="``:id´´" class="``:li_class´´">
        <a><i class="fa fa-folder"></i>``:name´´</a>
        <ul class="folder-list" style="padding-left: 10px;"></ul>
    </li>
</script>

<script class="tpl_filemanager_folder" type="text/x-jsrender">
    <div class="folder-box">
        <div class="folder">
            <a href="#">
                <span class="corner"></span>

                <div class="file-name">
                    ``:name´´
                </div>
                
            </a>
        </div>
    </div>
</script>

<script class="tpl_filemanager_file" type="text/x-jsrender">
    <div class="file-box">
        <div class="file">
            <a href="#">
                <span class="corner"></span>

                <div class="file-name">
                    ``:filename´´
                    <br/>
                    <small>``:date´´</small>
                </div>
                
            </a>
            <div class="btn-group" style="width: 100%">
                <button data-toggle="dropdown" class="btn btn-block btn-success dropdown-toggle" aria-expanded="false">Opcije <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li class="``if !enable_select_option´´hidden``/if´´">
                        <a href="#"
                            data-option="select"
                            class="js-file_option">
                            Izberi
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            data-option="get_url"
                            class="js-file_option">
                            Dobi povezavo
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            data-option="open"
                            class="js-file_option">
                            Odpri
                        </a>
                    </li>
                    <li class="danger">
                        <a href="#"
                            data-option="delete"
                            class="js-file_option">
                            Izbriši
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</script>

<script class="tpl_filemanager_attachable" type="text/x-jsrender">
    <div>
        <h2>Povezane datoteke</h2>
        <hr />
        <ul class="js-file_list"></ul>
        <hr />
        <div class="btn btn-primary js-open_filemanager">Odpri brskalnik</div>
    </div>
</script>

<script class="tpl_filemanager_attachable_file" type="text/x-jsrender">
    <li>
        <div class="btn btn-xs btn-danger js-detach_file"><i class="glyphicon glyphicon-remove"></i></div>
        ``:name´´
    </li>
</script>

<div class="modal inmodal fade"
    tabindex="-1"
    id="filemanager_modal_url"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Povezava</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" type="text" readonly="readonly" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Zapri</button>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade"
    tabindex="-1"
    id="filemanager_modal_app"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Filemanager</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Zapri</button>
            </div>
        </div>
    </div>
</div>
