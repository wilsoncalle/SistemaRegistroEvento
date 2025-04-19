const express = require('express'); 
const router = express.Router(); 
const registrosController = require('../controllers/registros'); 
 
router.get('/', registrosController.getRegistros); 
router.get('/:id', registrosController.getRegistroById); 
router.post('/', registrosController.createRegistro); 
router.put('/:id', registrosController.updateRegistro); 
router.delete('/:id', registrosController.deleteRegistro); 
 
module.exports = router; 
