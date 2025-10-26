<?php

class ValidationHelper {
    
    private $errors = [];
    
    public function validate($data, $rules) {
        $this->errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = isset($data[$field]) ? $data[$field] : null;
            
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule, $data);
            }
        }
        
        return empty($this->errors);
    }
    
    private function applyRule($field, $value, $rule, $allData) {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleValue = isset($ruleParts[1]) ? $ruleParts[1] : null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field][] = ucfirst($field) . " is required";
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = ucfirst($field) . " must be a valid email";
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->errors[$field][] = ucfirst($field) . " must be at least {$ruleValue} characters";
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->errors[$field][] = ucfirst($field) . " must not exceed {$ruleValue} characters";
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = ucfirst($field) . " must be a number";
                }
                break;
                
            case 'match':
                if (!empty($value) && $value !== $allData[$ruleValue]) {
                    $this->errors[$field][] = ucfirst($field) . " must match " . ucfirst($ruleValue);
                }
                break;
                
            case 'unique':
                // Format: unique:table,column
                $parts = explode(',', $ruleValue);
                $table = $parts[0];
                $column = isset($parts[1]) ? $parts[1] : $field;
                
                if (!empty($value) && $this->checkUnique($table, $column, $value)) {
                    $this->errors[$field][] = ucfirst($field) . " already exists";
                }
                break;
        }
    }
    
    private function checkUnique($table, $column, $value) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getFirstError($field = null) {
        if ($field) {
            return isset($this->errors[$field]) ? $this->errors[$field][0] : null;
        }
        
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0];
        }
        
        return null;
    }
    
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    public static function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
