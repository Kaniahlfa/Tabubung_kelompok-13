const express = require('express');
const router = express.Router();
const { insertSpending } = require('../controllers/spendingController');

router.post('/spending', insertSpending);

module.exports = router;
