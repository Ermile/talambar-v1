<?php
function transtext()
{

	// ------------------------------------------------------------------- Table accounts
	echo T_("accounts");            // Table accounts
	echo T_("account");             // account
	echo T_("id");                  // id
	echo T_("title");               // account_title
	echo T_("slug");                // account_slug
	echo T_("bank");                // bank_id
	echo T_("branch");              // account_branch
	echo T_("number");              // account_number
	echo T_("card");                // account_card
	echo T_("primarybalance");      // account_primarybalance
	echo T_("desc");                // account_desc
	echo T_("user");                // user_id
	echo T_("modified");            // date_modified
	echo T_("enable");              // Enum enable
	echo T_("disable");             // Enum disable
	echo T_("expire");              // Enum expire
	echo T_("goingtoexpire");       // Enum goingtoexpire

	// ------------------------------------------------------------------- Table addons
	echo T_("addons");              // Table addons
	echo T_("addon");               // addon
	echo T_("name");                // addon_name
	echo T_("slug");                // addon_slug
	echo T_("desc");                // addon_desc
	echo T_("status");              // addon_status
	echo T_("expire");              // addon_expire
	echo T_("installdate");         // addon_installdate

	// ------------------------------------------------------------------- Table attachmentmetas
	echo T_("attachmentmetas");     // Table attachmentmetas
	echo T_("attachmentmeta");      // attachmentmeta
	echo T_("attachment");          // attachment_id
	echo T_("cat");                 // attachmentmeta_cat
	echo T_("name");                // attachmentmeta_name
	echo T_("value");               // attachmentmeta_value
	echo T_("status");              // attachmentmeta_status
	echo T_("productcategory");     // Enum productcategory
	echo T_("product");             // Enum product
	echo T_("admin");               // Enum admin
	echo T_("banklogo");            // Enum banklogo
	echo T_("post");                // Enum post
	echo T_("system");              // Enum system
	echo T_("other");               // Enum other
	echo T_("file");                // Enum file
	echo T_("folder");              // Enum folder
	echo T_("normal");              // Enum normal
	echo T_("trash");               // Enum trash
	echo T_("deleted");             // Enum deleted
	echo T_("inprogress");          // Enum inprogress

	// ------------------------------------------------------------------- Table attachments
	echo T_("attachments");         // Table attachments
	echo T_("attachment");          // attachment
	echo T_("file");                // file_id
	echo T_("title");               // attachment_title
	echo T_("type");                // attachment_type
	echo T_("addr");                // attachment_addr
	echo T_("name");                // attachment_name
	echo T_("ext");                 // attachment_ext
	echo T_("size");                // attachment_size
	echo T_("desc");                // attachment_desc
	echo T_("parent");              // attachment_parent
	echo T_("depth");               // attachment_depth
	echo T_("count");               // attachment_count
	echo T_("order");               // attachment_order
	echo T_("status");              // attachment_status

	// ------------------------------------------------------------------- Table banks
	echo T_("banks");               // Table banks
	echo T_("bank");                // bank
	echo T_("title");               // bank_title
	echo T_("slug");                // bank_slug
	echo T_("website");             // bank_website
	echo T_("status");              // bank_status
	echo T_("approved");            // Enum approved
	echo T_("unapproved");          // Enum unapproved
	echo T_("spam");                // Enum spam

	// ------------------------------------------------------------------- Table comments
	echo T_("comments");            // Table comments
	echo T_("comment");             // comment
	echo T_("post");                // post_id
	echo T_("product");             // product_id
	echo T_("author");              // comment_author
	echo T_("email");               // comment_email
	echo T_("url");                 // comment_url
	echo T_("content");             // comment_content
	echo T_("status");              // comment_status
	echo T_("parent");              // comment_parent
	echo T_("visitor");             // Visitor_id
	echo T_("income");              // Enum income
	echo T_("outcome");             // Enum outcome

	// ------------------------------------------------------------------- Table costcats
	echo T_("costcats");            // Table costcats
	echo T_("costcat");             // costcat
	echo T_("title");               // costcat_title
	echo T_("slug");                // costcat_slug
	echo T_("desc");                // costcat_desc
	echo T_("parent");              // costcat_parent
	echo T_("order");               // costcat_order
	echo T_("type");                // costcat_type
	echo T_("status");              // costcat_status

	// ------------------------------------------------------------------- Table costs
	echo T_("costs");               // Table costs
	echo T_("cost");                // cost
	echo T_("title");               // cost_title
	echo T_("price");               // cost_price
	echo T_("costcat");             // costcat_id
	echo T_("account");             // account_id
	echo T_("date");                // cost_date
	echo T_("desc");                // cost_desc
	echo T_("type");                // cost_type

	// ------------------------------------------------------------------- Table errorlogs
	echo T_("errorlogs");           // Table errorlogs
	echo T_("errorlog");            // errorlog
	echo T_("errorlog");            // errorlog_id
	echo T_("critical");            // Enum critical
	echo T_("high");                // Enum high
	echo T_("medium");              // Enum medium
	echo T_("low");                 // Enum low

	// ------------------------------------------------------------------- Table errors
	echo T_("errors");              // Table errors
	echo T_("error");               // error
	echo T_("title");               // error_title
	echo T_("solution");            // error_solution
	echo T_("priority");            // error_priority
	echo T_("awaiting");            // Enum awaiting
	echo T_("start");               // Enum start
	echo T_("appended");            // Enum appended
	echo T_("failed");              // Enum failed
	echo T_("finished");            // Enum finished

	// ------------------------------------------------------------------- Table fileparts
	echo T_("fileparts");           // Table fileparts
	echo T_("filepart");            // filepart
	echo T_("part");                // filepart_part
	echo T_("code");                // filepart_code
	echo T_("status");              // filepart_status
	echo T_("ready");               // Enum ready
	echo T_("temp");                // Enum temp

	// ------------------------------------------------------------------- Table files
	echo T_("files");               // Table files
	echo T_("file");                // file
	echo T_("server");              // file_server
	echo T_("folder");              // file_folder
	echo T_("code");                // file_code
	echo T_("size");                // file_size
	echo T_("status");              // file_status

	// ------------------------------------------------------------------- Table funds
	echo T_("funds");               // Table funds
	echo T_("fund");                // fund
	echo T_("title");               // fund_title
	echo T_("slug");                // fund_slug
	echo T_("location");            // location_id
	echo T_("initialbalance");      // fund_initialbalance
	echo T_("desc");                // fund_desc

	// ------------------------------------------------------------------- Table locations
	echo T_("locations");           // Table locations
	echo T_("location");            // location
	echo T_("title");               // location_title
	echo T_("slug");                // location_slug
	echo T_("desc");                // location_desc
	echo T_("status");              // location_status
	echo T_("read");                // Enum read
	echo T_("unread");              // Enum unread

	// ------------------------------------------------------------------- Table notifications
	echo T_("notifications");       // Table notifications
	echo T_("notification");        // notification
	echo T_("user sender");         // user_id_sender
	echo T_("title");               // notification_title
	echo T_("content");             // notification_content
	echo T_("url");                 // notification_url
	echo T_("status");              // notification_status

	// ------------------------------------------------------------------- Table options
	echo T_("options");             // Table options
	echo T_("option");              // option
	echo T_("cat");                 // option_cat
	echo T_("name");                // option_name
	echo T_("value");               // option_value
	echo T_("extra");               // option_extra
	echo T_("status");              // option_status
	echo T_("pass");                // Enum pass
	echo T_("recovery");            // Enum recovery
	echo T_("fail");                // Enum fail
	echo T_("lost");                // Enum lost
	echo T_("block");               // Enum block
	echo T_("delete");              // Enum delete

	// ------------------------------------------------------------------- Table papers
	echo T_("papers");              // Table papers
	echo T_("paper");               // paper
	echo T_("type");                // paper_type
	echo T_("number");              // paper_number
	echo T_("date");                // paper_date
	echo T_("price");               // paper_price
	echo T_("holder");              // paper_holder
	echo T_("desc");                // paper_desc
	echo T_("status");              // paper_status
	echo T_("yes");                 // Enum yes
	echo T_("no");                  // Enum no

	// ------------------------------------------------------------------- Table permissions
	echo T_("permissions");         // Table permissions
	echo T_("permission");          // permission
	echo T_("title");               // permission_title
	echo T_("module");              // Permission_module
	echo T_("view");                // permission_view
	echo T_("add");                 // permission_add
	echo T_("edit");                // permission_edit
	echo T_("delete");              // permission_delete
	echo T_("status");              // permission_status

	// ------------------------------------------------------------------- Table postmetas
	echo T_("postmetas");           // Table postmetas
	echo T_("postmeta");            // postmeta
	echo T_("cat");                 // postmeta_cat
	echo T_("name");                // postmeta_name
	echo T_("value");               // postmeta_value
	echo T_("status");              // postmeta_status
	echo T_("page");                // Enum page
	echo T_("open");                // Enum open
	echo T_("closed");              // Enum closed
	echo T_("publish");             // Enum publish
	echo T_("draft");               // Enum draft
	echo T_("schedule");            // Enum schedule

	// ------------------------------------------------------------------- Table posts
	echo T_("posts");               // Table posts
	echo T_("post");                // post
	echo T_("language");            // post_language
	echo T_("cat");                 // post_cat
	echo T_("title");               // post_title
	echo T_("slug");                // post_slug
	echo T_("content");             // post_content
	echo T_("type");                // post_type
	echo T_("comment");             // post_comment
	echo T_("count");               // post_count
	echo T_("status");              // post_status
	echo T_("parent");              // post_parent
	echo T_("publishdate");         // post_publishdate

	// ------------------------------------------------------------------- Table productcats
	echo T_("productcats");         // Table productcats
	echo T_("productcat");          // productcat
	echo T_("title");               // productcat_title
	echo T_("slug");                // productcat_slug
	echo T_("desc");                // productcat_desc
	echo T_("parent");              // productcat_parent
	echo T_("order");               // productcat_order
	echo T_("status");              // productcat_status
	echo T_("count");               // productcat_count

	// ------------------------------------------------------------------- Table productmetas
	echo T_("productmetas");        // Table productmetas
	echo T_("productmeta");         // productmeta
	echo T_("cat");                 // productmeta_cat
	echo T_("name");                // productmeta_name
	echo T_("value");               // productmeta_value
	echo T_("status");              // productmeta_status

	// ------------------------------------------------------------------- Table productprices
	echo T_("productprices");       // Table productprices
	echo T_("productprice");        // productprice
	echo T_("productmeta");         // productmeta_id
	echo T_("cat");                 // productprice_cat
	echo T_("startdate");           // productprice_startdate
	echo T_("enddate");             // productprice_enddate
	echo T_("buyprice");            // productprice_buyprice
	echo T_("price");               // productprice_price
	echo T_("discount");            // productprice_discount
	echo T_("vat");                 // productprice_vat
	echo T_("status");              // productprice_status
	echo T_("unset");               // Enum unset
	echo T_("available");           // Enum available
	echo T_("soon");                // Enum soon
	echo T_("discontinued");        // Enum discontinued
	echo T_("unavailable");         // Enum unavailable
	echo T_("store");               // Enum store
	echo T_("online");              // Enum online
	echo T_("both");                // Enum both

	// ------------------------------------------------------------------- Table products
	echo T_("products");            // Table products
	echo T_("product");             // product
	echo T_("title");               // product_title
	echo T_("slug");                // product_slug
	echo T_("productcat");          // productcat_id
	echo T_("barcode");             // product_barcode
	echo T_("barcode2");            // product_barcode2
	echo T_("buyprice");            // product_buyprice
	echo T_("price");               // product_price
	echo T_("discount");            // product_discount
	echo T_("vat");                 // product_vat
	echo T_("initialbalance");      // product_initialbalance
	echo T_("mininventory");        // product_mininventory
	echo T_("status");              // product_status
	echo T_("sold");                // product_sold
	echo T_("stock");               // product_stock
	echo T_("carton");              // product_carton
	echo T_("service");             // product_service
	echo T_("sellin");              // product_sellin

	// ------------------------------------------------------------------- Table receipts
	echo T_("receipts");            // Table receipts
	echo T_("receipt");             // receipt
	echo T_("code");                // receipt_code
	echo T_("type");                // receipt_type
	echo T_("price");               // receipt_price
	echo T_("date");                // receipt_date
	echo T_("paper");               // paper_id
	echo T_("paperdate");           // receipt_paperdate
	echo T_("paperstatus");         // receipt_paperstatus
	echo T_("desc");                // receipt_desc
	echo T_("transaction");         // transaction_id
	echo T_("fund");                // fund_id
	echo T_("user customer");       // user_id_customer
	echo T_("get");                 // Enum get
	echo T_("receive");             // Enum receive
	echo T_("delivery");            // Enum delivery

	// ------------------------------------------------------------------- Table smss
	echo T_("smss");                // Table smss
	echo T_("sms");                 // sms
	echo T_("from");                // sms_from
	echo T_("to");                  // sms_to
	echo T_("message");             // sms_message
	echo T_("messageid");           // sms_messageid
	echo T_("deliverystatus");      // sms_deliverystatus
	echo T_("method");              // sms_method
	echo T_("type");                // sms_type
	echo T_("createdate");          // sms_createdate
	echo T_("status");              // sms_status
	echo T_("cat");                 // Enum cat
	echo T_("tag");                 // Enum tag

	// ------------------------------------------------------------------- Table terms
	echo T_("terms");               // Table terms
	echo T_("term");                // term
	echo T_("language");            // term_language
	echo T_("title");               // term_title
	echo T_("slug");                // term_slug
	echo T_("desc");                // term_desc
	echo T_("parent");              // term_parent
	echo T_("type");                // term_type
	echo T_("status");              // term_status
	echo T_("count");               // term_count
	echo T_("order");               // term_order

	// ------------------------------------------------------------------- Table termusages
	echo T_("termusages");          // Table termusages
	echo T_("termusage");           // termusage
	echo T_("term");                // term_id

	// ------------------------------------------------------------------- Table transactiondetails
	echo T_("transactiondetails");  // Table transactiondetails
	echo T_("transactiondetail");   // transactiondetail
	echo T_("row");                 // transactiondetail_row
	echo T_("quantity");            // transactiondetail_quantity
	echo T_("price");               // transactiondetail_price
	echo T_("discount");            // transactiondetail_discount

	// ------------------------------------------------------------------- Table transactionmetas
	echo T_("transactionmetas");    // Table transactionmetas
	echo T_("transactionmeta");     // transactionmeta
	echo T_("cat");                 // transactionmeta_cat
	echo T_("name");                // transactionmeta_name
	echo T_("value");               // transactionmeta_value
	echo T_("status");              // transactionmeta_status
	echo T_("sale");                // Enum sale
	echo T_("purchase");            // Enum purchase
	echo T_("customertostore");     // Enum customertostore
	echo T_("storetocompany");      // Enum storetocompany
	echo T_("anbargardani");        // Enum anbargardani
	echo T_("install");             // Enum install
	echo T_("repair");              // Enum repair
	echo T_("chqeuebackfail");      // Enum chqeuebackfail

	// ------------------------------------------------------------------- Table transactions
	echo T_("transactions");        // Table transactions
	echo T_("transaction");         // transaction
	echo T_("type");                // transaction_type
	echo T_("date");                // transaction_date
	echo T_("sum");                 // transaction_sum
	echo T_("remained");            // transaction_remained
	echo T_("forgetpassword");      // Enum forgetpassword

	// ------------------------------------------------------------------- Table userlogs
	echo T_("userlogs");            // Table userlogs
	echo T_("userlog");             // userlog
	echo T_("title");               // userlog_title
	echo T_("desc");                // userlog_desc
	echo T_("priority");            // userlog_priority
	echo T_("type");                // userlog_type

	// ------------------------------------------------------------------- Table usermetas
	echo T_("usermetas");           // Table usermetas
	echo T_("usermeta");            // usermeta
	echo T_("cat");                 // usermeta_cat
	echo T_("name");                // usermeta_name
	echo T_("value");               // usermeta_value
	echo T_("extra");               // usermeta_extra
	echo T_("status");              // usermeta_status
	echo T_("storeadmin");          // Enum storeadmin
	echo T_("storeemployee");       // Enum storeemployee
	echo T_("storesupplier");       // Enum storesupplier
	echo T_("storecustomer");       // Enum storecustomer
	echo T_("user");                // Enum user
	echo T_("male");                // Enum male
	echo T_("female");              // Enum female
	echo T_("active");              // Enum active
	echo T_("deactive");            // Enum deactive
	echo T_("removed");             // Enum removed
	echo T_("filter");              // Enum filter

	// ------------------------------------------------------------------- Table users
	echo T_("users");               // Table users
	echo T_("user");                // user
	echo T_("type");                // user_type
	echo T_("mobile");              // user_mobile
	echo T_("pass");                // user_pass
	echo T_("email");               // user_email
	echo T_("gender");              // user_gender
	echo T_("nickname");            // user_nickname
	echo T_("firstname");           // user_firstname
	echo T_("lastname");            // user_lastname
	echo T_("birthday");            // user_birthday
	echo T_("status");              // user_status
	echo T_("credit");              // user_credit
	echo T_("permission");          // permission_id
	echo T_("createdate");          // user_createdate
	echo T_("emailsignup");         // Enum emailsignup
	echo T_("emailchangepass");     // Enum emailchangepass
	echo T_("emailrecovery");       // Enum emailrecovery
	echo T_("mobilesignup");        // Enum mobilesignup
	echo T_("mobilechangepass");    // Enum mobilechangepass
	echo T_("mobilerecovery");      // Enum mobilerecovery

	// ------------------------------------------------------------------- Table verifications
	echo T_("verifications");       // Table verifications
	echo T_("verification");        // verification
	echo T_("type");                // verification_type
	echo T_("value");               // verification_value
	echo T_("code");                // verification_code
	echo T_("url");                 // verification_url
	echo T_("verified");            // verification_verified
	echo T_("status");              // verification_status
	echo T_("createdate");          // verification_createdate

	// ------------------------------------------------------------------- Table visitors
	echo T_("visitors");            // Table visitors
	echo T_("visitor");             // visitor
	echo T_("ip");                  // visitor_ip
	echo T_("url");                 // visitor_url
	echo T_("agent");               // visitor_agent
	echo T_("referer");             // visitor_referer
	echo T_("robot");               // visitor_robot
	echo T_("createdate");          // visitor_createdate

}
?>