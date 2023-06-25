<?php

Flight::route('GET /connection-check', function () {
    /** TODO
     * This endpoint prints the message from constructor within MidtermDao class
     * Goal is to check whether connection is successfully established or not
     * This endpoint does not have to return output in JSON format
     */
    Flight::midtermService();
});

Flight::route('GET /cap-table', function () {
    /** TODO
     * This endpoint returns list of all share classes within table named cap_table
     * Each class contains description field named 'class' and array of all categories within given class
     * Each category contains description field named 'category' and array of all investors that have shares within given category
     * Each investor has fields: 'diluted_shares' and 'investor' which is obtained by concatanation of first and last name of the investor
     * Outpus is given in figure 2
     * This endpoint should return output in JSON format
     */
    Flight::json(Flight::midtermService()->cap_table());
});

Flight::route('POST /cap-table-record', function () {
    /** TODO
     * This endpoint is used to add new record to cap-table database table. If added successfully output should be the added array with the id of the new record
     * Example output is given in figure 3
     * This endpoint should return output in JSON format
     */
    $request = Flight::request()->data->getData();
    $id = $request['id'];
    $share_class_id = $request['share_class_id'];
    $share_class_category_id = $request['share_class_category_id'];
    $investor_id = $request['investor_id'];
    $diluted_shares = $request['diluted_shares'];

    return Flight::json(Flight::midtermService()->add_cap_table_record($id, $share_class_id, $share_class_category_id, $investor_id, $diluted_shares));
});


Flight::route('GET /categories', function () {
    /** TODO
     * This endpoint returns list of all categories with the total amount of diluted_shares for each category
     * Output example is given in figure 4
     * This endpoint should return output in JSON format
     */
    Flight::json(Flight::midtermService()->categories());
});

Flight::route("DELETE /investor/@id", function ($id) {
    /** TODO
     * This endpoint is used to delete investor
     * Endpoint should return the message whether investor has been deleted
     * This endpoint should return output in JSON format
     */
    Flight::json(Flight::midtermService()->delete_investor($id)
        ? ["status" => "success", "message" => "Investor deleted successfully."]
        : ["status" => "error", "message" => "Failed to delete investor."]);
});