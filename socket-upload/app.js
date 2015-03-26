var io = require('socket.io')(8000),
    fs = require('fs'),
    asyn = require('async'),
    db = require('./db');

var UPLOAD_DIR = __dirname + '/uploads/';

io.on('connection', function(socket) {
  socket.on('meta', function(meta) {
    console.log(meta);
    socket.meta = meta;
    if(meta.fileID) {
      updateFile(meta.fileID, socket.id, answerMeta.bind(socket));
    } else {
      addFile(meta, socket.id, answerMeta.bind(socket));
    }
  });
  socket.on('data', function(data) {
    if(!socket.meta || !socket.fs) return;

    socket.fs.write(data, function() {
      socket.emit('data-answer');
    })

  });

  socket.on('resume-status', function(data) {
    if(!data || !data.fileID) {
      socket.emit('resume-status', 0);
      return;
    }

    fs.stat(UPLOAD_DIR + data.fileID, function(err, stats) {
      if(err) return console.log('ERR', err);
      console.log(stats.size);
      socket.emit('resume-status', stats.size);
      console.log(socket.fs);
    });
  });

  var des = destroyRecords.bind(socket);

  socket.on('end', des);
  socket.on('disconnect', des);
  socket.on('error', des);
});

function addFile(meta, socketId, cb) {
  asyn.waterfall([
    function(next) {
      db.File.create({
        file_folder: meta.parent || 0,
        file_code: socketId,
        file_size: meta.size,
        file_status: 'inprogress',
        file_server: 1
      }).then(function(file) {
        next(null, file);
      }, next);
    },
    function(file, next) {
      db.FilePart.create({
        file_id: file.id,
        filepart_part: 0,
        filepart_code: socketId,
        filepart_status: 'inprogress'
      }).then(function(filepart) {
        next(null, file, filepart);
      }, next);
    },
    function(file, filepart, next) {
      db.Attachment.create({
        file_id: file.id,
        attachment_title: meta.file,
        attachment_type: 'file',
        attachment_addr: meta.addr,
        attachment_size: meta.size,
        attachment_parent: meta.parent,
        attachment_count: 0,
        attachment_order: 0,
        user_id: meta.user || 190
      }).then(function(attachment) {
        next(null, file, filepart, attachment);
      }, next);
    }
  ], cb);
}

function updateFile(id, socketId, cb) {
  asyn.waterfall([
    function(next) {
      db.File.find(id).then(function(file) {
        next(null, file);
      }, next);
    },
    function(file, next) {
      db.FilePart.findOrCreate({
        where: {
          file_id: id
        },
        file_id: id,
        filepart_part: 0,
        filepart_code: socketId,
        filepart_status: 'inprogress'
      }).then(function(filepart) {
        next(null, file, filepart);
      }, next);
    },
    function(file, filepart, next) {
      db.Attachment.find({where: {file_id: id}})
      .then(function(attachment) {
        next(null, file, filepart, attachment, true);
      }, next);
    }
  ], cb);
}

function moveFile(name, id) {
  fs.renameSync(UPLOAD_DIR + name, UPLOAD_DIR + id);
}


/* this => socket */
function answerMeta(err, file, filepart, attachment) {
  if(err) return console.log('Error: ', err);

  console.log('answerMeta');
  this.fs = fs.createWriteStream(UPLOAD_DIR + file.id, {flags: 'a'});

  this.file = file;
  this.filepart = filepart;
  this.emit('ready', file.id);
}

function destroyRecords() {
  if(this.filepart && this.file) {
    this.filepart.destroy();
    this.file.update({file_code: null});
  }
}