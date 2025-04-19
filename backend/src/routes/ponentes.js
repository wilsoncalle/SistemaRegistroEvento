const express = require('express'); 
const router = express.Router(); 
const ponentesController = require('../controllers/ponentes'); 
 
router.get('/', ponentesController.getPonentes); 
router.get('/:id', ponentesController.getPonenteById); 
router.post('/', ponentesController.createPonente); 
router.put('/:id', ponentesController.updatePonente); 
router.delete('/:id', ponentesController.deletePonente); 
 
module.exports = router; 
