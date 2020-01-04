<div class="container">
    <div class="starter-template">
        <div class="row text-center">
            <div class="col-md-12">
                <form id="search_form" class="form-inline">
                    <div class="form-group">
                        <!--<label class="sr-only" for="search_key">Search</label>-->
                        <input type="text" class="form-control" id="search_key" placeholder="Search keyword e.g. tom,toma" required>
                    </div>
                    <input type="text" name="formsubmitfix" class="hidden" />
                    <input type="button" class="btn btn-default btn-search" value="Search"/>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nume</th>
                            <th>Initiala</th>
                            <th>Prenume</th>
                            <th>Cuim</th>
                            <th>ID</th>
                            <th><input type="checkbox" name="checkAll"></th>
                        </tr>
                    </thead>
                    <tbody class="searchedData">
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="allSearchedData" value="" />
        </div>
        <!--<hr>-->
        <div class="row text-center btnInsWrapper hidden">
            <div class="col-md-12">
                <input type="button" class="btn btn-primary btn-insert" value="Insert"/>
            </div>
        </div>
    </div>
</div>