const express = require('express');
const router = express.Router();

const {
  insertIncome,
} = require('../controllers/incomeController');

router.post('/income', insertIncome);

module.exports = router;