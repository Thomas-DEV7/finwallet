## **Uso de Design Patterns**

1.  **Repository Pattern**  
    Separação da lógica de acesso a dados:
    
    -   Implementação de queries complexas no repositório em vez de controllers.

2.  **Factory Pattern**  
    Criação de objetos como transações e usuários:
    
    -   Uso de **Seeders** e **Faker** para instanciar objetos de maneira padronizada.

3.  **Strategy Pattern**  
    Diferentes comportamentos de transações:
    
    -   Ex.: `Transaction` manipula transferências, depósitos e reembolsos com lógicas específicas por tipo.

4.  **Observer Pattern**  
    Eventos e listeners:
    
    -   Exemplo em potencial: Criação de um listener para atualizações de saldo em transações.

5.  **Middleware**  
    Atuação como uma implementação do **Chain of Responsibility**:
    
    -   Verificação de autenticação e autorização de usuários.

6.  **Template Method Pattern**  
    Uso de Blade Templates:
    
    -   Layouts como `guest.blade.php` e `dashboard.blade.php` definem a estrutura base, enquanto as views específicas completam o conteúdo.

